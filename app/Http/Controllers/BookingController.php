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

class BookingController extends Controller
{
    public function create($fleetId)
    {
        try {
            $car = Fleet::where('plateNumber', $fleetId)->firstOrFail();       
            $vehicleInfo = $this->getVehicleInfoForDisplay($car->modelName, $car->year);
            $pricePerDay = $car->price_per_day ?? $vehicleInfo['price'];       
            $image = $vehicleInfo['image'];
            $vehicleName = $car->modelName . ($car->year ? ' ' . $car->year : '');

            try {
                // Fetch active rewards for display if needed
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

        $price = 120;
        if (strpos($modelName, 'bezza') !== false) { $price = 140; }
        elseif (strpos($modelName, 'myvi') !== false && $year >= 2020) { $price = 150; }
        elseif (strpos($modelName, 'axia') !== false && $year == 2024) { $price = 130; }
        elseif (strpos($modelName, 'alza') !== false) { $price = 200; }        
        elseif (strpos($modelName, 'aruz') !== false) { $price = 180; }        
        elseif (strpos($modelName, 'vellfire') !== false) { $price = 500; }
        elseif (strpos($modelName, 'x50') !== false) { $price = 250; }
        elseif (strpos($modelName, 'y15') !== false) { $price = 50; }

        return ['price' => $price, 'image' => $image];
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('login')->with('error', 'Please login to make a booking.');
        }

        // 1. Dynamic Validation Rules
        $identityRule = ($customer->doc_ic_passport || $customer->doc_matric) ? 'nullable' : 'required';
        $licenseRule  = ($customer->doc_license) ? 'nullable' : 'required';
        
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
            
            // Files (PDF supported)
            'identity_document' => "$identityRule|file|mimes:jpeg,png,jpg,pdf|max:5120",
            'driving_license'   => "$licenseRule|file|mimes:jpeg,png,jpg,pdf|max:5120",
            'id_type'           => 'nullable|in:ic,matric',
            'receipt'           => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',

            // Bank Details
            'payer_bank'    => 'nullable|string|max:255',
            'payer_account' => 'nullable|string|max:255',
        ]);

        if ( (!$customer->bankName || !$customer->accountNum) && (!$request->payer_bank || !$request->payer_account) ) {
            return back()->with('error', 'Refund bank details are required.')->withInput();
        }

        DB::beginTransaction();

        try {
            // --- A. UPDATE CUSTOMER PROFILE (Documents) ---
            if ($request->hasFile('identity_document')) {
                $path = $request->file('identity_document')->store('documents', 'public');
                if ($request->id_type === 'ic') {
                    $customer->doc_ic_passport = $path;
                } else {
                    $customer->doc_matric = $path;
                }
            }

            if ($request->hasFile('driving_license')) {
                $path = $request->file('driving_license')->store('documents', 'public');
                $customer->doc_license = $path;
            }

            // --- B. UPDATE CUSTOMER PROFILE (Bank Details) ---
            if ($request->filled('payer_bank')) {
                $customer->bankName = $request->payer_bank;
            }
            if ($request->filled('payer_account')) {
                // Remove dashes/spaces
                $sanitizedAccount = preg_replace('/[^0-9]/', '', $request->payer_account);
                $customer->accountNum = $sanitizedAccount; 
            }
            
            if ($customer->isDirty()) {
                $customer->save();
            }

            // --- C. PROCESS BOOKING ---
            $fleet = Fleet::where('plateNumber', $validated['plateNumber'])->firstOrFail();
            $pickupDateTime = $validated['start_date'] . ' ' . $validated['start_time'];
            $returnDateTime = $validated['end_date'] . ' ' . $validated['end_time'];

            $start = new \DateTime($validated['start_date']);
            $end = new \DateTime($validated['end_date']);
            $days = $end->diff($start)->days ?: 1;

            $pricePerDay = $fleet->price_per_day ?: $this->calculatePriceFromModel($fleet->modelName, $fleet->year);
            $basePrice = $pricePerDay * $days;
            $discount = 0;
            $appliedRewardId = null;

            // Handle Rewards/Vouchers using REWARD table
            if ($request->voucher_code) {
                // Check using voucherCode column
                $reward = Reward::where('voucherCode', $request->voucher_code)
                                  ->where('rewardStatus', 'Active') // Ensure using your status string
                                  ->first();
                
                if ($reward && $reward->isValidCode()) {
                    // Calculate Discount based on type
                    if (isset($reward->rewardType) && strtolower($reward->rewardType) === 'fixed') {
                        $discount += $reward->rewardAmount;
                    } else {
                        // Default to percentage if type is Percentage or undefined
                        $discount += ($basePrice * $reward->rewardAmount / 100);
                    }
                    $reward->incrementUsage();
                    $appliedRewardId = $reward->rewardID;
                }
            } elseif ($request->input('reward_id')) {
                // Fallback for ID selection
                $reward = Reward::find($request->input('reward_id'));
                if ($reward && $reward->rewardStatus === 'Active') {
                    if (isset($reward->rewardType) && strtolower($reward->rewardType) === 'fixed') {
                        $discount += $reward->rewardAmount;
                    } else {
                        $discount += ($basePrice * $reward->rewardAmount / 100);
                    }
                    $appliedRewardId = $reward->rewardID;
                }
            }

            $finalPrice = max(0, $basePrice - $discount);
            $depositAmount = $request->input('deposit_amount', $fleet->deposit ?? 50);
            $totalPrice = $request->total_amount;

            $bookingID = 'BK' . strtoupper(uniqid());

            // Handle Receipt Upload
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')->store('receipts', 'public');
            }

            $booking = Booking::create([
                'bookingID'   => $bookingID,
                'matricNum'   => $customer->matricNum,
                'plateNumber' => $validated['plateNumber'],
                'rewardID'    => $appliedRewardId,
                'pickupDate'  => $pickupDateTime,
                'returnDate'  => $returnDateTime,
                'pickupLoc'   => $validated['pickup_location'],
                'returnLoc'   => $validated['return_location'],
                'deposit'     => (float)$depositAmount,
                'totalPrice'  => (float)$totalPrice,
                'bookingStat' => 'pending',
                'feedback'    => null,
                'paymentReceipt' => $receiptPath
            ]);

            DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Booking created successfully! ID: #' . $booking->bookingID);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function validateVoucher(Request $request)
    {
        try {
            // Check 'rewards' table using 'voucherCode'
            $reward = Reward::where('voucherCode', $request->voucher_code)
                            ->where('rewardStatus', 'Active')
                            ->first();

            if ($reward && $reward->isValidCode()) {
                return response()->json([
                    'valid' => true, 
                    'discount' => $reward->rewardAmount, // Map this to JS
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
        // 1. Fetch the booking first. This makes $booking and its data available.
        $booking = Booking::with(['fleet', 'reward'])
            ->where('bookingID', $bookingId)
            ->firstOrFail();

        // 2. Permission Check
        $isStaff = Auth::guard('staff')->check();
        $isOwner = auth()->id() == $booking->matricNum;

        if (!$isStaff && !$isOwner) {
            return redirect()->route('bookings.index')->with('error', 'Unauthorized access.');
        }

        // 3. Define variables for the pricing breakdown
        // Use the dates already stored in the $booking object
        $start = new \DateTime($booking->pickupDate);
        $end = new \DateTime($booking->returnDate);
        $days = $end->diff($start)->days ?: 1;

        // 4. Calculate basePrice (Total minus Deposit)
        // This ensures the numbers match what the user saw during payment
        $basePrice = (float)$booking->totalPrice - (float)$booking->deposit;

        // 5. Pass these variables to the view
        return view('bookings.show', compact('booking', 'basePrice'));

    } catch (\Exception $e) {
        // If anything fails, this will now tell you why (e.g., "Undefined variable")
        return redirect()->route('bookings.index')->with('error', 'Booking not found: ' . $e->getMessage());
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

    private function calculatePriceFromModel($modelName, $year = null)
    {
        $modelName = strtolower($modelName);
        if (strpos($modelName, 'bezza') !== false) return 140;
        if (strpos($modelName, 'myvi') !== false && $year >= 2020) return 150; 
        if (strpos($modelName, 'axia') !== false && $year == 2024) return 130; 
        if (strpos($modelName, 'alza') !== false) return 200;
        if (strpos($modelName, 'aruz') !== false) return 180;
        if (strpos($modelName, 'vellfire') !== false) return 500;
        if (strpos($modelName, 'x50') !== false) return 250;
        if (strpos($modelName, 'y15') !== false) return 50;
        return 120;
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
        // 1. Initialize variables to prevent "Undefined variable" errors
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

        // 2. Logic to set amount (Fix for undefined variable)
        if (!is_null($requestedTotal) && $requestedTotal !== '') {
            $full_amount = (float) $requestedTotal;
        } else {
            if (!is_null($requestedPricePerDay) && $requestedPricePerDay !== '') {
                $pricePerDay = (float) $requestedPricePerDay;
            } elseif (!empty($car->price_per_day)) {
                $pricePerDay = $car->price_per_day;
            } else {
                $pricePerDay = $this->calculatePriceFromModel($car->modelName, $car->year);
            }
            $full_amount = $days * $pricePerDay;
        }

        $deposit_amount = !is_null($requestedDeposit) && $requestedDeposit !== ''
            ? (float) $requestedDeposit
            : ($car->deposit ?? 200.00);

        $total_with_deposit = $full_amount + $deposit_amount;

        return view('bookings.payment', [
            'booking_data' => $data,
            'full_amount' => $full_amount, // Validated
            'deposit_amount' => $deposit_amount,
            'total_with_deposit' => $total_with_deposit,
            'car' => $car,
            'customer' => $customer // <--- REQUIRED for verify identity section
        ]);
    }

    public function approve($bookingID)
    {
        if (!Auth::guard('staff')->check()) {
            return back()->with('error', 'Unauthorized access.');
        }

        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();

        if ($booking->bookingStat != 'pending') {
            return back()->with('error', 'Invalid booking approval.');
        }

        $booking->update(['bookingStat' => 'confirmed']);

        return back()->with('success', 'Booking approved!');
    }

    public function filterBookings(Request $request) 
    {
        // 1. Start a base query (do not use ->get() yet)
        $query = Booking::with('fleet', 'customer');

        // 2. Apply Search Filter (Search by Booking ID or Plate Number)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bookingID', 'LIKE', "%{$search}%")
                ->orWhere('plateNumber', 'LIKE', "%{$search}%");
            });
        }

        // 3. Apply Status Filter
        if ($request->has('status') && $request->status != '') {
            $query->where('bookingStat', $request->status);
        }

        // 4. Fetch the filtered results
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        // 5. Calculate counts (Keep these as they are for your metric cards)
        $totalBookings = Booking::count();
        $confirmedCount = Booking::where('bookingStat', 'confirmed')->count();
        $pendingCount = Booking::where('bookingStat', 'pending')->count();
        $completedCount = Booking::where('bookingStat', 'completed')->count();
        $cancelledCount = Booking::where('bookingStat', 'cancelled')->count();

        return view('staff.bookingmanagement', compact(
            'bookings', 'totalBookings', 'confirmedCount', 
            'pendingCount', 'completedCount', 'cancelledCount'
        ));
    }

    public function uploadForms(Request $request, $bookingID)
    {
        // 1. Validate inputs
        $request->validate([
            'pickupForm' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Increased limit to 5MB
            'returnForm' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();

        // 2. Handle Pickup Upload
        if ($request->hasFile('pickupForm')) {
            // Delete old image if exists (optional cleanup)
            // if ($booking->pickupForm) Storage::disk('public')->delete($booking->pickupForm);

            // Store new file
            $path = $request->file('pickupForm')->store('inspections', 'public');
            $booking->pickupForm = $path;
        }

        // 3. Handle Return Upload
        if ($request->hasFile('returnForm')) {
            $path = $request->file('returnForm')->store('inspections', 'public');
            $booking->returnForm = $path;
        }

        // 4. Force save (Bypasses $fillable issues)
        $booking->save();

        return back()->with('success', 'Inspection images saved successfully!');
    }
    
public function showPickupForm($bookingID)
    {
        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();
        
        // NEW: Fetch the saved inspection data so the form isn't empty
        $inspection = Inspection::where('bookingID', $bookingID)
                                ->where('type', 'pickup')
                                ->first();

        // Pass BOTH variables to the view
        return view('bookings.pickup', compact('booking', 'inspection'));
    }

    // REPLACE your existing showReturnForm with this:
    public function showReturnForm($bookingID)
    {
        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();
        
        // NEW: Fetch the saved inspection data
        $inspection = Inspection::where('bookingID', $bookingID)
                                ->where('type', 'return')
                                ->first();

        return view('bookings.return', compact('booking', 'inspection'));
    }

    public function storeInspection(Request $request, $bookingID)
    {
        // 1. Validate inputs
        $request->validate([
            'type' => 'required|in:pickup,return',
            'fuel_image' => 'required|image|max:5120',
            'fuel_level' => 'required|numeric',
            'mileage' => 'required|numeric',
            'notes' => 'nullable|string',
            'confirm' => 'accepted',
            'signature' => 'required', // Ensure signature is present
            'photo_front' => 'nullable|image|max:5120',
            'photo_back'  => 'nullable|image|max:5120',
            'photo_left'  => 'nullable|image|max:5120',
            'photo_right' => 'nullable|image|max:5120',
        ]);

        $booking = Booking::findOrFail($bookingID);

        // 2. Initialize Inspection Model
        $inspection = new \App\Models\Inspection(); // Ensure correct model path
        $inspection->inspectionID = 'INS-' . time() . '-' . Str::random(4);
        $inspection->bookingID = $booking->bookingID;
        $inspection->type = $request->type;
        $inspection->mileage = $request->mileage;
        $inspection->fuelBar = $request->fuel_level;
        $inspection->remark = $request->notes;
        $inspection->dateOut = now();
        $inspection->time = now();

        // 3. PROCESS SIGNATURE (Base64 -> Image File)
        if ($request->filled('signature')) {
            $base64_image = $request->input('signature');
            
            // Strip the header "data:image/png;base64," if present
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
                $type = strtolower($type[1]); // png

                $base64_decode = base64_decode($base64_image);

                if ($base64_decode !== false) {
                    $filename = 'sig_' . time() . '_' . Str::random(5) . '.' . $type;
                    $path = 'inspections/signatures/' . $filename;
                    
                    // Save to public storage
                    Storage::disk('public')->put($path, $base64_decode);
                    
                    // Save path to DB column
                    $inspection->signature = $path;
                }
            }
        }

        // 4. Handle Fuel Image
        if ($request->hasFile('fuel_image')) {
            $inspection->fuelImage = $request->file('fuel_image')->store('inspections/fuel', 'public');
        }

        // 5. Handle Car Photos
        $views = ['front' => 'frontViewImage', 'back' => 'backViewImage', 'left' => 'leftViewImage', 'right' => 'rightViewImage'];
        foreach ($views as $formName => $dbColumn) {
            if ($request->hasFile("photo_{$formName}")) {
                $inspection->$dbColumn = $request->file("photo_{$formName}")->store('inspections/vehicle', 'public');
            }
        }

        // 6. Save the Inspection Record
        $inspection->save();

        // 7. Update Booking Status (Marks the form as "Completed" in the Booking table)
        // We use the current timestamp so !empty() returns true in your View
        if ($request->type === 'pickup') {
            $booking->pickupForm = now(); 
        } else {
            $booking->returnForm = now();
        }
        $booking->save();

        // 8. REDIRECT BACK TO DETAILS PAGE
        // This sends the user back to the main details page with a success banner
        return redirect()->route('bookings.show', $bookingID)
            ->with('success', ucfirst($request->type) . ' inspection form submitted successfully!');
    }


}