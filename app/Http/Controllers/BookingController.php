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
            $car = Fleet::findOrFail($fleetId);
            
            // Get active rewards or return empty collection if table doesn't exist
            try {
                $rewards = Reward::where('active', true)->get();
            } catch (\Exception $e) {
                $rewards = collect([]);
            }
            
            return view('bookings.create', compact('car', 'rewards'));
        } catch (\Exception $e) {
            return back()->with('error', 'Fleet not found: ' . $e->getMessage());
        }
    }

    /**
     * Store the booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fleet_id' => 'required|exists:fleet,id',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_date' => 'required|date|after:start_date',
            'end_time' => 'required',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'reward_id' => 'nullable|exists:reward,id',
            'voucher_code' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            $fleet = Fleet::findOrFail($validated['fleet_id']);
            
            // Calculate rental period
            $startDate = $validated['start_date'];
            $endDate = $validated['end_date'];
            
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $days = $end->diff($start)->days;
            
            if ($days < 1) {
                $days = 1;
            }
            
            // Calculate base price
            $basePrice = $fleet->price_per_day * $days;
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
            
            // Create booking with your table structure
            $booking = Booking::create([
                'customer_id' => auth()->id() ?? 1, // Temporary: Use 1 if not authenticated
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
                'total_price' => $finalPrice,
                'deposit' => $fleet->deposit ?? 0,
                'booking_stat' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Booking created successfully! Booking ID: ' . $booking->booking_id);
                
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
            $booking = Booking::with(['fleet', 'reward'])
                ->where('booking_id', $bookingId)
                ->firstOrFail();
            
            return view('bookings.show', compact('booking'));
        } catch (\Exception $e) {
            return back()->with('error', 'Booking not found');
        }
    }

    /**
     * Show user's bookings
     */
    public function index()
    {
        try {
            $bookings = Booking::with('fleet')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('bookings.index', compact('bookings'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading bookings');
        }
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
}