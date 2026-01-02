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
            $car = Fleet::where('fleet_id', $fleetId)->firstOrFail();
            
            // Get vehicle info for display
            $vehicleInfo = $this->getVehicleInfoForDisplay($car->model_name, $car->year);
            $pricePerDay = $car->price_per_day ?? $vehicleInfo['price'];
            $image = $vehicleInfo['image'];
            $vehicleName = $car->model_name . ($car->year ? ' ' . $car->year : '');
            
            // Get active rewards or return empty collection if table doesn't exist
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

    /**
     * Get vehicle info for display (similar to VehicleController)
     */
    private function getVehicleInfoForDisplay($modelName, $year = null)
    {
        $modelName = strtolower($modelName);
        $year = $year ?? 0;
        
        // Determine image filename
        $image = 'default-car.png';
        if (strpos($modelName, 'axia') !== false) {
            $image = $year == 2024 ? 'axia-2024.png' : 'axia-2018.png';
        } elseif (strpos($modelName, 'bezza') !== false) {
            $image = 'bezza-2018.png';
        } elseif (strpos($modelName, 'myvi') !== false) {
            $image = $year >= 2020 ? 'myvi-2020.png' : 'myvi-2015.png';
        } elseif (strpos($modelName, 'saga') !== false) {
            $image = 'saga-2017.png';
        } elseif (strpos($modelName, 'alza') !== false) {
            $image = 'alza-2019.png';
        } elseif (strpos($modelName, 'aruz') !== false) {
            $image = 'aruz-2020.png';
        } elseif (strpos($modelName, 'vellfire') !== false) {
            $image = 'vellfire-2020.png';
        }
        
        // Determine price
        $price = 120; // default
        if (strpos($modelName, 'bezza') !== false) {
            $price = 140;
        } elseif (strpos($modelName, 'myvi') !== false && $year >= 2020) {
            $price = 150;
        } elseif (strpos($modelName, 'axia') !== false && $year == 2024) {
            $price = 130;
        } elseif (strpos($modelName, 'alza') !== false) {
            $price = 200;
        } elseif (strpos($modelName, 'aruz') !== false) {
            $price = 180;
        } elseif (strpos($modelName, 'vellfire') !== false) {
            $price = 500;
        }
        
        return [
            'price' => $price,
            'image' => $image
        ];
    }

    /**
     * Store the booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fleet_id' => 'required|exists:fleet,fleet_id',
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

        DB::beginTransaction();
        
        try {
            $fleet = Fleet::where('fleet_id', $validated['fleet_id'])->firstOrFail();
            
            // Calculate rental period
            $startDate = $validated['start_date'];
            $endDate = $validated['end_date'];
            
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $days = $end->diff($start)->days;
            
            if ($days < 1) {
                $days = 1;
            }
            
            // Calculate base price - use price_per_day if available, otherwise calculate from model
            $pricePerDay = $fleet->price_per_day;
            if (!$pricePerDay || $pricePerDay == 0) {
                $pricePerDay = $this->calculatePriceFromModel($fleet->model_name, $fleet->year);
            }
            
            // Calculate base price
            $basePrice = $pricePerDay * $days;
            $discount = 0;
            
            // Apply reward discount
            if ($request->reward_id) {
                $reward = Reward::find($request->reward_id);
                if ($reward && $reward->active) {
                    $discount += ($basePrice * $reward->discount / 100);
                }
            }
            
            // Apply voucher discount
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)
                    ->where('active', true)
                    ->where('valid_until', '>=', now())
                    ->first();
                    
                if ($voucher && $voucher->isValid()) {
                    $discount += ($basePrice * $voucher->discount / 100);
                    $voucher->incrementUsage();
                }
            }
            
            $finalPrice = $basePrice - $discount;
            
            // Get customer_id from authenticated user (using user ID as customer_id for now)
            $customerId = auth()->id();
            if (!$customerId) {
                return redirect()->route('login')
                    ->with('error', 'Please login to make a booking.');
            }
            
            // Create booking with your table structure
            $booking = Booking::create([
                'customer_id' => $customerId,
                'fleet_id' => $validated['fleet_id'],
                'reward_id' => $request->reward_id,
                'voucher_code' => $request->voucher_code,
                'pickup_date' => $startDate,
                'pickup_time' => $validated['start_time'],
                'return_date' => $endDate,
                'return_time' => $validated['end_time'],
                'pickup_loc' => $validated['pickup_location'],
                'return_loc' => $validated['return_location'],
                'base_price' => $basePrice,
                'discount' => $discount,
                'total_price' => $finalPrice + ($request->input('deposit_amount') ? (float)$request->input('deposit_amount') : 50),
                'deposit' => $request->input('deposit_amount', 50),
                'booking_stat' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->notes ?? null,
            ]);
            
            DB::commit();
            
            return redirect()->route('bookings.index')
                ->with('success', 'Booking created successfully! Booking ID: #' . $booking->booking_id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while creating the booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Validate voucher code
     */
    public function validateVoucher(Request $request)
    {
        try {
            $voucher = Voucher::where('code', $request->voucher_code)
                ->where('active', true)
                ->where('valid_until', '>=', now())
                ->first();
            
            if ($voucher && $voucher->isValid()) {
                return response()->json([
                    'valid' => true,
                    'discount' => $voucher->discount,
                    'message' => 'Voucher applied successfully'
                ]);
            }
            
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired voucher code'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Error validating voucher'
            ], 500);
        }
    }

    /**
     * Show booking details
     */
    public function show($bookingId)
    {
        try {
            $customerId = auth()->id();
            
            if (!$customerId) {
                return redirect()->route('login')
                    ->with('error', 'Please login to view booking details.');
            }
            
            $booking = Booking::with(['fleet', 'reward'])
                ->where('booking_id', $bookingId)
                ->where('customer_id', $customerId)
                ->firstOrFail();
            
            return view('bookings.show', compact('booking'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('bookings.index')
                ->with('error', 'Booking not found or you do not have permission to view it.');
        } catch (\Exception $e) {
            return redirect()->route('bookings.index')
                ->with('error', 'An error occurred while loading the booking.');
        }
    }

    /**
     * Show user's bookings
     */
    public function index()
    {
        try {
            // Filter bookings by current authenticated user
            $customerId = auth()->id();
            
            if (!$customerId) {
                return redirect()->route('login')
                    ->with('error', 'Please login to view your bookings.');
            }
            
            $bookings = Booking::with('fleet')
                ->where('customer_id', $customerId)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('customer.bookings', compact('bookings'));
            
        } catch (\Exception $e) {
            return redirect()->route('book-now')
                ->with('error', 'Error loading bookings: ' . $e->getMessage());
        }
    }

    /**
     * Calculate price from model name (fallback if price_per_day is not set)
     */
    private function calculatePriceFromModel($modelName, $year = null)
    {
        $modelName = strtolower($modelName);
        $year = $year ?? 0;
        
        if (strpos($modelName, 'bezza') !== false) {
            return 140;
        } elseif (strpos($modelName, 'myvi') !== false && $year >= 2020) {
            return 150;
        } elseif (strpos($modelName, 'axia') !== false && $year == 2024) {
            return 130;
        } elseif (strpos($modelName, 'alza') !== false) {
            return 200;
        } elseif (strpos($modelName, 'aruz') !== false) {
            return 180;
        } elseif (strpos($modelName, 'vellfire') !== false) {
            return 500;
        }
        
        return 120; // default price
    }

    /**
     * Cancel booking
     */
    public function cancel($bookingId)
    {
        try {
            $booking = Booking::where('booking_id', $bookingId)->firstOrFail();
            
            if ($booking->booking_stat === 'completed' || $booking->booking_stat === 'cancelled') {
                return back()->with('error', 'This booking cannot be cancelled.');
            }
            
            $booking->update(['booking_stat' => 'cancelled']);
            
            return back()->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error cancelling booking');
        }
    }

    public function payment(Request $request)
    {
        // 1. Validate the booking details
        $request->validate([
            'fleet_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_location' => 'required',
            'return_location' => 'required',
        ]);

        // 2. Fetch car details
        $car = \App\Models\Fleet::findOrFail($request->fleet_id);
        
        // 3. Calculate rental days
        $start = new \DateTime($request->start_date);
        $end = new \DateTime($request->end_date);
        $diff = $start->diff($end);
        $days = $diff->days ?: 1; // If same day, count as 1 day

        // 4. Calculate both payment options
        // Prefer values passed from the previous form (hidden inputs) if present,
        // otherwise fall back to server-side calculation.
        $requestedTotal = $request->input('total_amount');
        $requestedPricePerDay = $request->input('price_per_day');
        $requestedDeposit = $request->input('deposit_amount');

        if (!is_null($requestedTotal) && $requestedTotal !== '') {
            $full_amount = (float) $requestedTotal;
        } else {
            $pricePerDay = null;
            if (!is_null($requestedPricePerDay) && $requestedPricePerDay !== '') {
                $pricePerDay = (float) $requestedPricePerDay;
            } elseif (!empty($car->price_per_day)) {
                $pricePerDay = $car->price_per_day;
            } else {
                $pricePerDay = $this->calculatePriceFromModel($car->model_name, $car->year);
            }

            $full_amount = $days * $pricePerDay;
        }

        $deposit_amount = !is_null($requestedDeposit) && $requestedDeposit !== ''
            ? (float) $requestedDeposit
            : ($car->deposit ?? 200.00); // Use car deposit or default to 200

        // 5. Compute total with deposit and send variables to the view
        $total_with_deposit = $full_amount + $deposit_amount;

        return view('bookings.payment', [
            'booking_data' => $request->all(),
            'full_amount' => $full_amount,
            'deposit_amount' => $deposit_amount,
            'total_with_deposit' => $total_with_deposit,
            'car' => $car
        ]);
    }

}