<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Reward;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Show the booking form
     */
    public function create($fleetId)
    {
        try {
            $car = Fleet::where('plateNumber', $fleetId)->firstOrFail();
            $vehicleInfo = $this->getVehicleInfoForDisplay($car->modelName, $car->year);
            $pricePerDay = $car->price_per_day ?? $vehicleInfo['price'];
            $image = $vehicleInfo['image'];
            $vehicleName = $car->modelName . ($car->year ? ' ' . $car->year : '');
            
            try {
                $rewards = Reward::where('active', true)->get();
            } catch (\Exception $e) {
                $rewards = collect([]);
            }
            
            return view('bookings.create', compact('car', 'rewards', 'pricePerDay', 'image', 'vehicleName'));
        } catch (\Exception $e) {
            return redirect()->route('book-now')
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

        $price = 120;
        if (strpos($modelName, 'bezza') !== false) { $price = 140; }
        elseif (strpos($modelName, 'myvi') !== false && $year >= 2020) { $price = 150; }
        elseif (strpos($modelName, 'axia') !== false && $year == 2024) { $price = 130; }
        elseif (strpos($modelName, 'alza') !== false) { $price = 200; }
        elseif (strpos($modelName, 'aruz') !== false) { $price = 180; }
        elseif (strpos($modelName, 'vellfire') !== false) { $price = 500; }

        return ['price' => $price, 'image' => $image];
    }

    /**
     * Store the booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plateNumber' => 'required|exists:fleet,plateNumber',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_date' => 'required|date|after:start_date',
            'end_time' => 'required',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'reward_id' => 'nullable|exists:reward,id',
            'voucher_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customerId = auth()->id();
        if (!$customerId) {
            return redirect()->route('login')->with('error', 'Please login to make a booking.');
        }

        DB::beginTransaction();
        
        try {
            $fleet = Fleet::where('plateNumber', $validated['plateNumber'])->firstOrFail();
            $pickupDateTime = $validated['start_date'] . ' ' . $validated['start_time'];
            $returnDateTime = $validated['end_date'] . ' ' . $validated['end_time'];

            $start = new \DateTime($validated['start_date']);
            $end = new \DateTime($validated['end_date']);
            $days = $end->diff($start)->days ?: 1;
            
            $pricePerDay = $fleet->price_per_day ?: $this->calculatePriceFromModel($fleet->modelName, $fleet->year);
            $basePrice = $pricePerDay * $days;
            $discount = 0;
            
            if ($request->reward_id) {
                $reward = Reward::find($request->reward_id);
                if ($reward && $reward->active) {
                    $discount += ($basePrice * $reward->discount / 100);
                }
            }
            
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->where('active', true)->where('valid_until', '>=', now())->first();
                if ($voucher && $voucher->isValid()) {
                    $discount += ($basePrice * $voucher->discount / 100);
                    $voucher->incrementUsage();
                }
            }
            
            $finalPrice = $basePrice - $discount;
            $depositAmount = $request->input('deposit_amount', $fleet->deposit ?? 50);
            $totalPrice = $finalPrice + (float)$depositAmount;

            // Generate ID before creation
            $bookingID = 'BK' . strtoupper(uniqid()); 

            $booking = Booking::create([
                'bookingID'   => $bookingID,
                'matricNum'   => $customerId,
                'plateNumber' => $validated['plateNumber'],
                'rewardID'    => $request->input('reward_id') ?? null,
                'pickupDate'  => $pickupDateTime,
                'returnDate'  => $returnDateTime,
                'pickupLoc'   => $validated['pickup_location'],
                'returnLoc'   => $validated['return_location'],
                'deposit'     => (float)$depositAmount,
                'totalPrice'  => (float)$totalPrice,
                'bookingStat' => 'pending',
                'feedback'    => null,
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
            $voucher = Voucher::where('code', $request->voucher_code)->where('active', true)->where('valid_until', '>=', now())->first();
            if ($voucher && $voucher->isValid()) {
                return response()->json(['valid' => true, 'discount' => $voucher->discount, 'message' => 'Voucher applied']);
            }
            return response()->json(['valid' => false, 'message' => 'Invalid voucher'], 400);
        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => 'Error'], 500);
        }
    }

    public function show($bookingId)
    {
        try {
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
            $customerId = auth()->id();
            if (!$customerId) return redirect()->route('login');
            
            $bookings = Booking::with('fleet')->where('matricNum', $customerId)->orderBy('created_at', 'desc')->paginate(10);
            return view('customer.bookings', compact('bookings'));
        } catch (\Exception $e) {
            return redirect()->route('book-now')->with('error', 'Error loading bookings');
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
        return 120;
    }

    public function cancel($bookingId)
    {
        try {
            $booking = Booking::where('bookingID', $bookingId)->firstOrFail();
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
        $request->validate([
            'plateNumber' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_location' => 'required',
            'return_location' => 'required',
        ]);

        $car = Fleet::where('plateNumber', $request->plateNumber)->firstOrFail();
        $days = (new \DateTime($request->start_date))->diff(new \DateTime($request->end_date))->days ?: 1;

        $full_amount = $request->input('total_amount') ?: ($days * ($request->input('price_per_day') ?: ($car->price_per_day ?: $this->calculatePriceFromModel($car->modelName, $car->year))));
        $deposit_amount = $request->input('deposit_amount') ?: ($car->deposit ?? 200.00);

        return view('bookings.payment', [
            'booking_data' => $request->all(),
            'full_amount' => (float)$full_amount,
            'deposit_amount' => (float)$deposit_amount,
            'total_with_deposit' => (float)$full_amount + (float)$deposit_amount,
            'car' => $car
        ]);
    }
}