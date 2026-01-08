<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Inspection;
use App\Models\Payment; // Import Payment

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

            try {
                $rewards = Reward::where('rewardStatus', 'Active')->get();
            } catch (\Exception $e) {
                $rewards = collect([]);
            }

            return view('bookings.create', compact('car', 'rewards', 'pricePerDay', 'image', 'vehicleName'));
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
        $customer = \Illuminate\Support\Facades\Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('login')->with('error', 'Please login.');
        }

        $validated = $request->validate([
            'plateNumber' => 'required|exists:fleet,plateNumber',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'reward_id' => 'nullable', 
            'voucher_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string', 
            'payer_bank' => 'nullable|string|max:255',
            'payer_account' => 'nullable|string|max:255',
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'total_amount' => 'required|numeric',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            if ($request->filled('payer_bank')) $customer->bankName = $request->payer_bank;
            if ($request->filled('payer_account')) $customer->accountNum = preg_replace('/[^0-9]/', '', $request->payer_account);
            if ($customer->isDirty()) $customer->save();

            $fleet = \App\Models\Fleet::where('plateNumber', $validated['plateNumber'])->firstOrFail();
            
            // Recalculate Logic
            $start = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
            $end = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time']);
            $diffInSeconds = abs($end->timestamp - $start->timestamp);
            $days = ceil($diffInSeconds / 86400); 
            $days = $days < 1 ? 1 : $days;

            $pricePerDay = $fleet->price; 
            $deposit = $request->input('deposit_amount', 50);

            $basePrice = ($days * $pricePerDay) + $deposit;
            $finalPrice = $request->total_amount;
            $discountAmount = 0;
            
            if ($request->filled('voucher_code')) {
                 $discountAmount = max(0, $basePrice - $finalPrice);
            }

            $bookingID = 'BK' . strtoupper(uniqid());
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
            $appliedRewardId = null; 

            $booking = \App\Models\Booking::create([
                'bookingID'    => $bookingID,
                'matricNum'    => $customer->matricNum,
                'plateNumber'  => $validated['plateNumber'],
                'rewardID'     => $appliedRewardId,
                'pickupDate'   => $validated['start_date'] . ' ' . $validated['start_time'],
                'returnDate'   => $validated['end_date'] . ' ' . $validated['end_time'],
                'pickupLoc'    => $validated['pickup_location'],
                'returnLoc'    => $validated['return_location'],
                'deposit'      => $deposit,
                'totalPrice'   => $finalPrice,
                'bookingStat'  => 'pending',
                'paymentReceipt' => $receiptPath
            ]);

            // CREATE PAYMENT RECORD
            Payment::create([
                'paymentID'       => 'PAY' . strtoupper(uniqid()), 
                'bookingID'       => $bookingID,
                'paymentStatus'   => 'pending',
                'method'          => $request->payment_method,
                'paymentDate'     => now(),
                'amount'          => $basePrice,
                'discountedPrice' => $discountAmount,
                'grandTotal'      => $finalPrice,
                'receipt_path'    => $receiptPath 
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Booking submitted successfully! ID: #' . $bookingID);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Submission Failed: ' . $e->getMessage())->withInput();
        }
    }

    public function validateVoucher(Request $request)
    {
        try {
            $reward = Reward::where('voucherCode', $request->voucher_code)
                            ->where('rewardStatus', 'Active')
                            ->first();

            if ($reward && $reward->isValidCode()) {
                return response()->json([
                    'valid' => true, 
                    'discount' => $reward->rewardAmount, 
                    'type' => strtolower($reward->rewardType ?? 'percentage'), 
                    'message' => 'Voucher applied successfully!'
                ]);
            }
            
            return response()->json(['valid' => false, 'message' => 'Invalid or expired voucher'], 400);
        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => 'Error validating voucher'], 500);
        }
    }

    public function show($bookingId)
    {
        try {
            $booking = Booking::with(['fleet', 'reward', 'customer']) 
                ->where('bookingID', $bookingId)
                ->firstOrFail();

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
            $basePrice = (float)$booking->totalPrice - (float)$booking->deposit;

            if (request()->ajax()) {
                return view('bookings.partials.show_modal', compact('booking', 'basePrice', 'days'));
            }

            return view('bookings.show', compact('booking', 'basePrice'));

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
            $isStaff = Auth::guard('staff')->check();
            $query = Booking::where('bookingID', $bookingId);
            if (!$isStaff) {
                $query->where('matricNum', auth()->id());
            }
            $booking = $query->firstOrFail(); 
            
            if ($booking->bookingStat === 'completed' || $booking->bookingStat === 'cancelled') {
                return back()->with('error', 'Cannot cancel.');
            }
            $booking->update(['bookingStat' => 'cancelled']);
            return back()->with('success', 'cancelled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error cancelling');
        }
    }

    public function payment(Request $request)
    {
        $full_amount = 0;
        $deposit_amount = 0;
        $total_with_deposit = 0;
        $pricePerDay = 0;

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
            $full_amount = (float) $requestedTotal;
        } else {
            if (!is_null($requestedPricePerDay) && $requestedPricePerDay !== '') {
                $pricePerDay = (float) $requestedPricePerDay;
            } else {
                $pricePerDay = $car->price; 
            }
            $full_amount = $days * $pricePerDay;
        }

        $deposit_amount = !is_null($requestedDeposit) && $requestedDeposit !== ''
            ? (float) $requestedDeposit
            : ($car->deposit ?? 200.00);

        $total_with_deposit = $full_amount + $deposit_amount;

        return view('bookings.payment', [
            'booking_data' => $data,
            'full_amount' => $full_amount, 
            'deposit_amount' => $deposit_amount,
            'total_with_deposit' => $total_with_deposit,
            'car' => $car,
            'customer' => $customer 
        ]);
    }

    public function approve($id)
    {
        if (!\Illuminate\Support\Facades\Auth::guard('staff')->check()) {
            return back()->with('error', 'Unauthorized access.');
        }

        $booking = Booking::with('payment')
                            ->where('bookingID', $id)
                            ->first();

        if (!$booking) {
            return back()->with('error', "System Error: Could not find booking with ID '$id'");
        }

        if (strtolower($booking->bookingStat) !== 'pending') {
            return back()->with('error', 'Booking is already processed.');
        }

        $booking->update(['bookingStat' => 'approved']);

        if ($booking->payment) {
            $booking->payment->update(['paymentStatus' => 'paid']);
        }

        return back()->with('success', 'Booking approved and Payment marked as Paid!');
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
        
        $inspection = Inspection::where('bookingID', $bookingID)
                                ->where('type', 'pickup')
                                ->first();

        return view('bookings.pickup', compact('booking', 'inspection'));
    }

    public function showReturnForm($bookingID)
    {
        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();
        
        $inspection = Inspection::where('bookingID', $bookingID)
                                ->where('type', 'return')
                                ->first();

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

        $booking = Booking::findOrFail($bookingID);

        $inspection = new \App\Models\Inspection(); 
        $inspection->inspectionID = 'INS-' . time() . '-' . Str::random(4);
        $inspection->bookingID = $booking->bookingID;
        $inspection->type = $request->type;
        $inspection->mileage = $request->mileage;
        $inspection->fuelBar = $request->fuel_level;
        $inspection->remark = $request->notes;
        $inspection->dateOut = now();
        $inspection->time = now();

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

        if ($request->type === 'pickup') {
            $booking->pickupForm = now(); 
        } elseif ($request->type === 'return') {
            $booking->returnForm = now();
            $booking->bookingStat = 'completed';
        }
        $booking->save();

        return redirect()->route('bookings.show', $bookingID)
            ->with('success', ucfirst($request->type) . ' inspection form submitted successfully!');
    }
}