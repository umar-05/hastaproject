<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            $totalPrice = $finalPrice + (float)$depositAmount;

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
            if (Auth::guard('staff')->check()) {
                $booking = Booking::with(['fleet', 'reward'])
                    ->where('bookingID', $bookingId)
                    ->firstOrFail();
                return view('bookings.show', compact('booking'));
            }

            $customerId = auth()->id();
            if (!$customerId) return redirect()->route('login');

            $booking = Booking::with(['fleet', 'reward'])
                ->where('bookingID', $bookingId)
                ->where('matricNum', $customerId)
                ->firstOrFail();

            return view('bookings.show', compact('booking'));
        } catch (\Exception $e) {
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
            return back()->with('success', 'Cancelled.');
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
}