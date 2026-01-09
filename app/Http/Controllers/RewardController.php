<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reward;
use App\Models\RewardRedemption;

class RewardController extends Controller
{
    // ==========================================
    // CUSTOMER METHODS
    // ==========================================

    public function index()
    {
        $user = Auth::user();

        // Display all active rewards
        $availableRewards = Reward::active() 
            ->where(function($query) {
                $query->where('totalClaimable', 0) // unlimited
                      ->orWhereRaw('claimedCount < totalClaimable'); // or still has slots
            })
            ->orderBy('rewardPoints', 'asc')
            ->get();

        return view('reward.customer', compact('availableRewards'));
    }

    public function claimed()
    {
        $customer = Auth::user();

        // Get ALL redemptions (both Active and Used)
        $myRewards = RewardRedemption::with('reward', 'booking')
            ->where('matricNum', $customer->matricNum)
            ->orderBy('redemptionDate', 'desc')
            ->get();

        return view('reward.claimed', compact('myRewards'));
    }

    public function claim(Request $request) 
    {
        try {
            DB::beginTransaction();

            $customer = auth()->user(); 
            $reward = Reward::findOrFail($request->rewardID);

            // 1. Validate reward is still claimable
            if (!$reward->isValidCode()) {
                return back()->with('error', 'This reward is no longer available.');
            }

            // 2. Check if customer has enough stamps
            if ($customer->rewardPoints < $reward->rewardPoints) {
                return back()->with('error', 'Not enough stamps! You need ' . 
                    $reward->rewardPoints . ' stamps but only have ' . 
                    $customer->rewardPoints . ' stamps.');
            }

            // 3. CHECK IF ALREADY HAS ACTIVE VOUCHER FOR THIS REWARD
            // Prevent users from claiming multiple active vouchers of the same reward
            $hasActive = RewardRedemption::where('matricNum', $customer->matricNum)
                        ->where('rewardID', $reward->rewardID)
                        ->where('status', 'Active')
                        ->whereNull('bookingID')
                        ->exists();

            if ($hasActive) {
                return back()->with('error', 'You already have an active voucher for this reward. Please use it first!');
            }

            // 4. Check if reward has reached its claim limit
            if ($reward->totalClaimable > 0 && $reward->claimedCount >= $reward->totalClaimable) {
                return back()->with('error', 'This reward has reached its claim limit.');
            }

            // 5. Create the redemption (voucher in wallet)
            RewardRedemption::create([
                'matricNum' => $customer->matricNum,
                'rewardID' => $reward->rewardID,
                'redemptionDate' => now(),
                'status' => 'Active', // Active until used in a booking
                'bookingID' => null,  // Not yet linked to any booking
                'used_at' => null
            ]);

            // 6. DEDUCT STAMPS from customer
            $customer->decrement('rewardPoints', $reward->rewardPoints);
            
            // 7. Increment the reward's claimed count
            $reward->increment('claimedCount');

            DB::commit();

            return redirect()->route('rewards.claimed')
                ->with('success', 'Voucher claimed successfully! Check your wallet.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to claim reward: ' . $e->getMessage());
        }
    }

    /**
     * Get available vouchers for booking checkout (API endpoint)
     */
    public function getAvailableVouchers()
    {
        $customer = Auth::user();
        
        // Get only ACTIVE vouchers that haven't been used yet
        $vouchers = RewardRedemption::where('matricNum', $customer->matricNum)
            ->where('status', 'Active')
            ->whereNull('bookingID')
            ->with('reward')
            ->get()
            ->filter(function($redemption) {
                // Double-check the reward is still valid
                return $redemption->isUsable();
            });

        return response()->json($vouchers);
    }

    // ==========================================
    // STAFF METHODS
    // ==========================================

    public function staffIndex()
    {
        $activeRewards = Reward::where('rewardStatus', 'Active')->latest()->get();
        $inactiveRewards = Reward::where('rewardStatus', 'Inactive')->latest()->get();

        $stats = [
            'total'  => Reward::count(),
            'active' => $activeRewards->count(),
            'slots'  => Reward::sum('totalClaimable') - Reward::sum('claimedCount'),
        ];

        return view('staff.rewards', compact('activeRewards', 'inactiveRewards', 'stats'));
    }

    public function create()
    {
        return view('staff.rewards.create');
    }

    public function store(Request $request)
    {
        // Map frontend field names to database columns
        if ($request->has('name')) $request->merge(['voucherCode' => $request->name]);
        if ($request->has('points_required')) $request->merge(['rewardPoints' => $request->points_required]);
        if ($request->has('type')) $request->merge(['rewardType' => $request->type]);
        if ($request->has('value')) $request->merge(['rewardAmount' => $request->value]);
        if ($request->has('expiry_date')) $request->merge(['expiryDate' => $request->expiry_date]);

        $validated = $request->validate([
            'voucherCode'    => 'required|string|max:255',
            'rewardType'     => 'required|in:Discount,Extra Hours', 
            'rewardAmount'   => 'nullable|integer|min:1',
            'rewardPoints'   => 'required|integer|min:1',
            'totalClaimable' => 'required|integer|min:1', 
            'expiryDate'     => 'nullable|date',
            'rewardStatus'   => 'required|in:Active,Inactive',
        ]);

        // Generate new reward ID
        $latest = Reward::latest()->first();
        if (!$latest) {
            $newID = 'RWD001';
        } else {
            $number = intval(substr($latest->rewardID, 3)); 
            $newID = 'RWD' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        }

        Reward::create([
            'rewardID'       => $newID,
            'voucherCode'    => $validated['voucherCode'],
            'rewardType'     => $validated['rewardType'],
            'rewardAmount'   => $validated['rewardAmount'] ?? null,
            'rewardPoints'   => $validated['rewardPoints'],
            'totalClaimable' => $validated['totalClaimable'],
            'claimedCount'   => 0, // Initialize to 0
            'expiryDate'     => $validated['expiryDate'] ?? null,
            'rewardStatus'   => $validated['rewardStatus'],
        ]);

        return redirect()->route('staff.reward.index')
            ->with('status', 'Reward created successfully!');
    }

    public function edit($id)
    {
        $reward = Reward::findOrFail($id);
        return view('staff.rewards.edit', compact('reward'));
    }

    public function update(Request $request, $id)
    {
        $reward = Reward::where('rewardID', $id)->firstOrFail();

        // Map frontend field names to database columns
        if ($request->has('name')) $request->merge(['voucherCode' => $request->name]);
        if ($request->has('points_required')) $request->merge(['rewardPoints' => $request->points_required]);
        if ($request->has('type')) $request->merge(['rewardType' => $request->type]);
        if ($request->has('value')) $request->merge(['rewardAmount' => $request->value]);
        if ($request->has('expiry_date')) $request->merge(['expiryDate' => $request->expiry_date]);

        $validated = $request->validate([
            'voucherCode'    => 'required|string|max:255',
            'rewardType'     => 'required|in:Discount,Extra Hours',
            'rewardAmount'   => 'nullable|integer|min:1',
            'rewardPoints'   => 'required|integer|min:1',
            'totalClaimable' => 'required|integer|min:1',
            'expiryDate'     => 'nullable|date',
            'rewardStatus'   => 'required|in:Active,Inactive',
        ]);

        $reward->update([
            'voucherCode'    => $validated['voucherCode'],
            'rewardType'     => $validated['rewardType'],
            'rewardAmount'   => $validated['rewardAmount'] ?? null,
            'rewardPoints'   => $validated['rewardPoints'],
            'totalClaimable' => $validated['totalClaimable'],
            'expiryDate'     => $validated['expiryDate'] ?? null,
            'rewardStatus'   => $validated['rewardStatus'],
        ]);

        return redirect()->route('staff.reward.index')
            ->with('status', 'Reward updated successfully!');
    }

    public function destroy($id)
    {
        $reward = Reward::where('rewardID', $id)->firstOrFail();
        
        // Check if reward has active redemptions
        $hasActiveRedemptions = RewardRedemption::where('rewardID', $id)
            ->where('status', 'Active')
            ->exists();

        if ($hasActiveRedemptions) {
            return back()->with('error', 'Cannot delete reward with active vouchers. Please wait for all vouchers to be used or expired.');
        }

        $reward->delete();

        return redirect()->route('staff.reward.index')
            ->with('status', 'Reward deleted successfully!');
    }
}