<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Reward;
use App\Models\RewardRedemption; 
use App\Models\Inspection;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create($fleetId)
    {
        try {
            $car = Fleet::where('plateNumber', $fleetId)->firstOrFail();       
            $vehicleInfo = $this->getVehicleInfoForDisplay($car->modelName, $car->year);
            
            // Use database price
            $pricePerDay = $car->price;       
            
            $image = $vehicleInfo['image'];
            $vehicleName = $car->modelName . ($car->year ? ' ' . $car->year : '');

            // Get ACTIVE vouchers that haven't been used yet for the customer
            $userVouchers = collect([]);
            if (Auth::guard('customer')->check()) {
                $userVouchers = RewardRedemption::where('matricNum', Auth::guard('customer')->user()->matricNum)
                    ->where('status', 'Active')
                    ->whereNull('bookingID') // Only show vouchers not yet linked to a booking
                    ->with('reward') 
                    ->get()
                    ->filter(function($redemption) {
                        return $redemption->isUsable(); // Double check it's valid
                    });
            }

            $rewards = Reward::where('rewardStatus', 'Active')->get();

            return view('bookings.create', compact('car', 'rewards', 'userVouchers', 'pricePerDay', 'image', 'vehicleName'));

        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Vehicle not found: ' . $e->getMessage());     
        }
    }

    private function getVehicleInfoForDisplay($modelName, $year = null)        
    {
        $modelName = strtolower($modelName);
        $year = $year ?? 0;
        $image = 'default-car.png';

        if (strpos($modelName, 'axia') !== false) { $image = $year == 2024 ? 'axia-2024.png' : 'axia-2018.png'; }
        elseif (strpos($modelName, 'bezza') !== false) { $image = 'bezza-2018.png'; }
        elseif (strpos($modelName, 'myvi') !== false) { $image = $year >= 2020 ? 'myvi-2020.png' : 'myvi-2015.png'; }
        elseif (strpos($modelName, 'saga') !== false) { $image = 'saga-2017.png'; }
        elseif (strpos($modelName, 'alza') !== false) { $image = 'alza-2019.png'; }
        elseif (strpos($modelName, 'aruz') !== false) { $image = 'aruz-2020.png'; }
        elseif (strpos($modelName, 'vellfire') !== false) { $image = 'vellfire-2020.png'; }
        elseif (strpos($modelName, 'x50') !== false) { $image = 'x50-2024.png'; }
        elseif (strpos($modelName, 'y15') !== false) { $image = 'y15zr-2023.png'; }

        return ['image' => $image];
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('login')->with('error', 'Please login.');
        }

        $validated = $request->validate([
            'plateNumber'       => 'required|exists:fleet,plateNumber',
            'start_date'        => 'required|date',
            'start_time'        => 'required',
            'end_date'          => 'required|date',
            'end_time'          => 'required',
            'pickup_location'   => 'required|string|max:255',
            'return_location'   => 'required|string|max:255',
            'redemption_id'     => 'nullable|exists:rewardredemption,id',
            'payment_method'    => 'required|string',
            'payer_bank'        => 'nullable|string|max:255',
            'payer_account'     => 'nullable|string|max:255',
            'receipt'           => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'total_amount'      => 'required|numeric',
            'deposit_amount'    => 'nullable|numeric',
            // New Validation Rules
            'identity_document' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'id_type'           => 'nullable|in:ic,matric',
            'driving_license'   => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // 1. Update Customer Documents & Bank Info
            
            // Handle Identity Document (IC/Passport or Matric)
            if ($request->hasFile('identity_document')) {
                $path = $request->file('identity_document')->store('documents', 'public');
                
                if ($request->id_type === 'ic') {
                    $customer->doc_ic_passport = $path;
                } elseif ($request->id_type === 'matric') {
                    $customer->doc_matric = $path;
                }
            }

            // Handle Driving License
            if ($request->hasFile('driving_license')) {
                $path = $request->file('driving_license')->store('documents', 'public');
                $customer->doc_license = $path;
            }

            // Handle Bank Info
            if ($request->filled('payer_bank')) $customer->bankName = $request->payer_bank;
            if ($request->filled('payer_account')) $customer->accountNum = preg_replace('/[^0-9]/', '', $request->payer_account);
            
            // Save updates to customer profile
            if ($customer->isDirty()) $customer->save();

            // 2. Fetch Fleet
            $fleet = Fleet::where('plateNumber', $validated['plateNumber'])->firstOrFail();

            // 3. Recalculate Logic (Server-side calculation for security)
            $start = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
            $end = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time']);
            $diffInSeconds = abs($end->timestamp - $start->timestamp);
            $days = ceil($diffInSeconds / 86400); 
            $days = $days < 1 ? 1 : $days;

            $pricePerDay = $fleet->price; 
            $deposit = $request->input('deposit_amount', 50); // Default to 50 if not sent

            // Delivery fee (fixed)
            $deliveryFee = 15;

            // Calculate Base Price (include delivery fee)
            $basePrice = ($days * $pricePerDay) + $deposit + $deliveryFee;
            
            $discountAmount = 0;
            $appliedRewardId = null;

            // 4. Handle Voucher / Discount
            if ($request->filled('redemption_id')) {
                $redemption = RewardRedemption::find($request->redemption_id);

                // Validate ownership and usability
                if (!$redemption || $redemption->matricNum !== $customer->matricNum || !$redemption->isUsable()) {
                    DB::rollBack();
                    return back()->with('error', 'Invalid or expired voucher.')->withInput();
                }

                $reward = $redemption->reward;
                
                if ($reward->rewardType === 'Discount') {
                    // Percentage discount (applied to base price excluding deposit, usually, but here applied to total)
                    // Adjust logic if discount shouldn't apply to deposit
                    $subTotal = $days * $pricePerDay;
                    $discountAmount = ($subTotal * $reward->rewardAmount) / 100;
                } else {
                    // Fixed amount discount
                    $discountAmount = $reward->rewardAmount;
                }
                
                $appliedRewardId = $reward->rewardID;
            }

            // Ensure price doesn't go below 0 (or below deposit, depending on policy)
            $finalPrice = max(0, $basePrice - $discountAmount);

            // 5. Upload Receipt
            $bookingID = 'BK' . strtoupper(uniqid());
            $receiptPath = $request->file('receipt')->store('receipts', 'public');

            // 6. Create Booking
            $booking = Booking::create([
                'bookingID'      => $bookingID,
                'matricNum'      => $customer->matricNum,
                'plateNumber'    => $validated['plateNumber'],
                'rewardID'       => $appliedRewardId,
                'pickupDate'     => $validated['start_date'] . ' ' . $validated['start_time'],
                'returnDate'     => $validated['end_date'] . ' ' . $validated['end_time'],
                'pickupLoc'      => $validated['pickup_location'],
                'returnLoc'      => $validated['return_location'],
                'deposit'        => $deposit,
                'totalPrice'     => $finalPrice,
                'bookingStat'    => 'pending',
                'paymentReceipt' => $receiptPath
            ]);

            // 7. Mark voucher as USED
            if ($request->filled('redemption_id') && isset($redemption)) {
                $redemption->markAsUsed($bookingID);
            }

            // 8. Create Payment Record
            Payment::create([
                'paymentID'       => 'PAY' . strtoupper(uniqid()), 
                'bookingID'       => $bookingID,
                'paymentStatus'   => 'pending',
                'method'          => $request->payment_method,
                'paymentDate'     => now(),
                'amount'          => $basePrice, // includes delivery + deposit
                'discountedPrice' => $discountAmount,
                'grandTotal'      => $finalPrice,
                'receipt_path'    => $receiptPath 
            ]);

            DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Booking submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function approve($id)
    {
        if (!Auth::guard('staff')->check()) {
            return back()->with('error', 'Unauthorized access.');
        }

        $booking = Booking::with('payment')->where('bookingID', $id)->first();

        if (!$booking) {
            return back()->with('error', "System Error: Could not find booking with ID '$id'");
        }

        if (strtolower($booking->bookingStat) !== 'pending') {
            return back()->with('error', 'Booking is already processed.');
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['bookingStat' => 'approved']);

            if ($booking->payment) {
                $booking->payment->update(['paymentStatus' => 'paid']);
            }
        });

        return back()->with('success', 'Booking approved and Payment marked as Paid!');
    }

    public function reject($id)
    {
        if (!Auth::guard('staff')->check()) {
            return back()->with('error', 'Unauthorized access.');
        }

        try {
            DB::beginTransaction();

            $booking = Booking::where('bookingID', $id)->firstOrFail();
            
            // IMPORTANT: If this booking used a voucher, return it to the customer
            $redemption = RewardRedemption::where('bookingID', $id)->first();
            if ($redemption) {
                $redemption->update([
                    'status' => 'Active',
                    'bookingID' => null,
                    'used_at' => null
                ]);
            }

            $booking->update(['bookingStat' => 'rejected']);

            DB::commit();

            $message = 'Booking rejected.';
            if ($redemption) {
                $message .= ' Voucher returned to customer.';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject booking: ' . $e->getMessage());
        }
    }

    public function validateVoucher(Request $request)
    {
        try {
            // 1. Match the name sent from Javascript
            $code = $request->input('voucher_code') ?? $request->input('voucherCode');
            $customer = Auth::guard('customer')->user();

            if (!$customer) {
                return response()->json(['valid' => false, 'message' => 'Session expired. Please re-login.']);
            }

            // 2. Query the redemption record (Check if user OWNS the voucher)
            $redemption = RewardRedemption::where('matricNum', $customer->matricNum)
                ->where('status', 'Active')
                ->whereHas('reward', function ($query) use ($code) {
                    $query->where('voucherCode', $code);
                })
                ->with('reward')
                ->first();

            if (!$redemption) {
                return response()->json(['valid' => false, 'message' => 'Voucher not found or not owned by you.']);
            }

            // 3. Return data based on Reward model
            return response()->json([
                'valid' => true,
                'redemption_id' => $redemption->id,
                'discount' => $redemption->reward->rewardAmount,
                'type' => ($redemption->reward->rewardType === 'Discount' ? 'percentage' : 'fixed'),
                'message' => 'Voucher applied successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($bookingId)
    {
        try {
            $booking = Booking::with(['fleet', 'reward', 'customer']) 
                ->where('bookingID', $bookingId)
                ->firstOrFail();
            
            $payment = Payment::where('bookingID', $bookingId)->first();

            $isStaff = Auth::guard('staff')->check();
            $isOwner = auth()->id() == $booking->matricNum;

            if (!$isStaff && !$isOwner) {
                if (request()->ajax()) {
                    return response('<div class="p-4 text-red-600">Unauthorized access.</div>', 403);
                }
                return redirect()->route('bookings.index')->with('error', 'Unauthorized access.');
            }

            $start = new \DateTime($booking->pickupDate);
            $end = new \DateTime($booking->returnDate);
            $days = $end->diff($start)->days ?: 1;
            
            // Calculate base price cleanly
            $basePrice = (float)$booking->totalPrice - (float)$booking->deposit;

            if (request()->ajax()) {
                return view('bookings.partials.show_modal', compact('booking', 'basePrice', 'days'));
            }

            return view('bookings.show', compact('booking', 'payment', 'basePrice'));

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response('<div class="p-6 text-center text-red-500">Error: ' . $e->getMessage() . '</div>', 500);
            }
            return redirect()->route('bookings.index')->with('error', 'Booking not found.');
        }
    }

    public function index()
    {
        try {
            if (Auth::guard('staff')->check()) {
                $bookings = Booking::with('fleet')->orderBy('created_at', 'desc')->paginate(10);
                return view('staff.bookingmanagement', compact('bookings'));
            }

            $customerId = auth()->id();
            if (!$customerId) return redirect()->route('login');

            $bookings = Booking::with('fleet')->where('matricNum', $customerId)->orderBy('created_at', 'desc')->paginate(10);
            return view('customer.bookings', compact('bookings'));

        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')->with('error', 'Error loading bookings: ' . $e->getMessage());
        }
    }

    public function cancel($bookingId)
    {
        try {
            DB::beginTransaction();

            $isStaff = Auth::guard('staff')->check();
            $query = Booking::where('bookingID', $bookingId);
            
            if (!$isStaff) {
                $query->where('matricNum', auth()->id());
            }
            $booking = $query->firstOrFail(); 
            
            if ($booking->bookingStat === 'completed' || $booking->bookingStat === 'cancelled') {
                return back()->with('error', 'Cannot cancel.');
            }

            // Return Voucher
            $redemption = RewardRedemption::where('bookingID', $bookingId)->first();
            if ($redemption) {
                $redemption->update([
                    'status' => 'Active',
                    'bookingID' => null,
                    'used_at' => null
                ]);
            }

            $booking->update(['bookingStat' => 'cancelled']);

            DB::commit();

            $message = 'Booking cancelled.';
            if ($redemption) {
                $message .= ' Voucher returned to your wallet.';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error cancelling: ' . $e->getMessage());
        }
    }

    public function payment(Request $request)
    {
        $full_amount = 0;
        $deposit_amount = 0;
        $total_with_deposit = 0;
        $pricePerDay = 0;

        $deliveryFee = 15; // fixed delivery fee for display

        $data = $request->isMethod('post') ? $request->all() : session()->getOldInput();
        $customer = Auth::guard('customer')->user();

        if (empty($data) || !isset($data['plateNumber'])) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Booking information missing. Please start again.');
        }

        $plateNumber = $data['plateNumber'] ?? null;
        $start_date = $data['start_date'] ?? ($data['startDate'] ?? null);
        $end_date = $data['end_date'] ?? ($data['endDate'] ?? null);
        $pickup_location = $data['pickup_location'] ?? ($data['pickupLoc'] ?? null);
        $return_location = $data['return_location'] ?? ($data['returnLoc'] ?? null);

        if (!$plateNumber || !$start_date || !$end_date || !$pickup_location || !$return_location) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Incomplete booking information. Please start again.');
        }

        $car = Fleet::where('plateNumber', $plateNumber)->firstOrFail();

        $start = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        $diff = $start->diff($end);
        $days = $diff->days ?: 1;

        $requestedTotal = $data['total_amount'] ?? null;
        $requestedPricePerDay = $data['price_per_day'] ?? null;
        $requestedDeposit = $data['deposit_amount'] ?? null;

        if (!is_null($requestedTotal) && $requestedTotal !== '') {
            // The form may send a pre-calculated grand total (rental + deposit + delivery)
            // Respect that value for display (avoid double-counting deposit)
            $full_amount = (float) $requestedTotal;

            $deposit_amount = !is_null($requestedDeposit) && $requestedDeposit !== ''
                ? (float) $requestedDeposit
                : ($car->deposit ?? 200.00);

            // If the total already includes deposit, total_with_deposit should be same as full_amount
            $total_with_deposit = $full_amount;
        } else {
            if (!is_null($requestedPricePerDay) && $requestedPricePerDay !== '') {
                $pricePerDay = (float) $requestedPricePerDay;
            } else {
                $pricePerDay = $car->price; 
            }

            // full_amount here is rental subtotal + delivery
            $full_amount = ($days * $pricePerDay) + $deliveryFee;

            $deposit_amount = !is_null($requestedDeposit) && $requestedDeposit !== ''
                ? (float) $requestedDeposit
                : ($car->deposit ?? 200.00);

            $total_with_deposit = $full_amount + $deposit_amount;
        }

        return view('bookings.payment', [
            'booking_data' => $data,
            'full_amount' => $full_amount, 
            'deposit_amount' => $deposit_amount,
            'total_with_deposit' => $total_with_deposit,
            'car' => $car,
            'customer' => $customer 
        ]);
    }

    public function filterBookings(Request $request) 
    {
        $query = Booking::with('fleet', 'customer');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bookingID', 'LIKE', "%{$search}%")
                ->orWhere('plateNumber', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('bookingStat', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $totalBookings = Booking::count();
        $approvedCount = Booking::where('bookingStat', 'approved')->count();
        $pendingCount = Booking::where('bookingStat', 'pending')->count();
        $completedCount = Booking::where('bookingStat', 'completed')->count();
        $cancelledCount = Booking::where('bookingStat', 'cancelled')->count();

        return view('staff.bookingmanagement', compact(
            'bookings', 'totalBookings', 'approvedCount', 
            'pendingCount', 'completedCount', 'cancelledCount'
        ));
    }

    public function uploadForms(Request $request, $bookingID)
    {
        $request->validate([
            'pickupForm' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', 
            'returnForm' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();

        if ($request->hasFile('pickupForm')) {
            $path = $request->file('pickupForm')->store('inspections', 'public');
            $booking->pickupForm = $path;
        }

        if ($request->hasFile('returnForm')) {
            $path = $request->file('returnForm')->store('inspections', 'public');
            $booking->returnForm = $path;
        }

        $booking->save();

        return back()->with('success', 'Inspection images saved successfully!');
    }
    
    public function showPickupForm($bookingID)
    {
        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();
        $inspection = Inspection::where('bookingID', $bookingID)->where('type', 'pickup')->first();
        return view('bookings.pickup', compact('booking', 'inspection'));
    }

    public function showReturnForm($bookingID)
    {
        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();
        $inspection = Inspection::where('bookingID', $bookingID)->where('type', 'return')->first();
        return view('bookings.return', compact('booking', 'inspection'));
    }

    public function storeInspection(Request $request, $bookingID)
    {
        $request->validate([
            'type' => 'required|in:pickup,return',
            'fuel_image' => 'required|image|max:5120',
            'fuel_level' => 'required|numeric',
            'mileage' => 'required|numeric',
            'notes' => 'nullable|string',
            'confirm' => 'accepted',
            'signature' => 'required',
            'photo_front' => 'nullable|image|max:5120',
            'photo_back'  => 'nullable|image|max:5120',
            'photo_left'  => 'nullable|image|max:5120',
            'photo_right' => 'nullable|image|max:5120',
        ]);

        $booking = Booking::with('customer')->findOrFail($bookingID);

        $inspection = new Inspection();
        $inspection->inspectionID = 'INS-' . time() . '-' . Str::random(4);
        $inspection->bookingID = $booking->bookingID;
        $inspection->type = $request->type;
        $inspection->mileage = $request->mileage;
        $inspection->fuelBar = $request->fuel_level;
        $inspection->remark = $request->notes;
        $inspection->dateOut = now();
        $inspection->time = now();

        // Handle Signature (Base64)
        if ($request->filled('signature')) {
            $base64_image = $request->input('signature');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
                $type = strtolower($type[1]); 

                $base64_decode = base64_decode($base64_image);
                if ($base64_decode !== false) {
                    $filename = 'sig_' . time() . '_' . Str::random(5) . '.' . $type;
                    $path = 'inspections/signatures/' . $filename;
                    Storage::disk('public')->put($path, $base64_decode);
                    $inspection->signature = $path;
                }
            }
        }

        if ($request->hasFile('fuel_image')) {
            $inspection->fuelImage = $request->file('fuel_image')->store('inspections/fuel', 'public');
        }

        $views = ['front' => 'frontViewImage', 'back' => 'backViewImage', 'left' => 'leftViewImage', 'right' => 'rightViewImage'];
        foreach ($views as $formName => $dbColumn) {
            if ($request->hasFile("photo_{$formName}")) {
                $inspection->$dbColumn = $request->file("photo_{$formName}")->store('inspections/vehicle', 'public');
            }
        }

        $inspection->save();

        $earnedPoints = 0;

        // UPDATED STAMP LOGIC: 1 booking = 1 stamp (not based on days)
        if ($request->type === 'pickup') {
            $booking->pickupForm = now(); 
            if ($booking->bookingStat == 'approved') {
                $booking->bookingStat = 'active';
            }
        } elseif ($request->type === 'return') {
            $booking->returnForm = now();
            
            // Only award if transitioning to completed for the first time
            if ($booking->bookingStat !== 'completed') {
                $booking->bookingStat = 'completed';

                // Award 1 stamp per completed booking
                $earnedPoints = 1; 

                if ($booking->customer) {
                    $booking->customer->increment('rewardPoints', $earnedPoints);
                }
            }
        }
        $booking->save();

        $msg = ucfirst($request->type) . ' inspection submitted successfully!';
        if ($earnedPoints > 0) {
            $msg .= " Customer earned $earnedPoints stamp!";
        }

        return redirect()->route('bookings.show', $bookingID)
            ->with('success', $msg);
    }
}