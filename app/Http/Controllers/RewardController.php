<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;
use App\Models\RewardRedemption;

class RewardController extends Controller
{
    // ==========================================
    // CUSTOMER METHODS
    // ==========================================

    /**
     * Show the main loyalty rewards page (customer view).
     */
    public function index()
    {
        $user = Auth::user();

        $availableRewards = Reward::active() 
            ->where('totalClaimable', '>', 0)
            // Hide rewards the user has already claimed
            ->whereDoesntHave('redemptions', function($query) use ($user) {
                $query->where('matricNum', $user->matricNum);
            })
            ->get();

        return view('reward.customer', compact('availableRewards'));
    }

    /**
     * Show the user's claimed rewards.
     */
    public function claimed()
    {
        $customer = Auth::user();

        // Fetch rewards claimed by this user
        $myRewards = RewardRedemption::with('reward')
            ->where('matricNum', $customer->matricNum)
            ->get();

        return view('reward.claimed', compact('myRewards'));
    }

    /**
     * Process a reward claim (Customer Action).
     */
    public function claim(Request $request)
    {
        $request->validate(['rewardID' => 'required|exists:rewards,rewardID']); // Ensure table name is correct (usually plural 'rewards')
        
        $reward = Reward::where('rewardID', $request->rewardID)->firstOrFail();
        $customer = Auth::user();

        // 1. Logic: Ensure user has enough stamps/points
        if ($customer->stamps < $reward->rewardPoints) {
            return back()->with('error', 'Not enough stamps!');
        }

        // 2. Create the Redemption Record (FIXED: Was creating a Reward before)
        RewardRedemption::create([
            'matricNum' => $customer->matricNum,
            'rewardID' => $reward->rewardID,
            'redemptionDate' => now(),
            'status' => 'Active' // Optional, if you have a status column
        ]);

        // 3. Deduct Stamps from Customer (Optional but recommended)
        // $customer->decrement('stamps', $reward->rewardPoints);

        // 4. Reduce slots available
        $reward->decrement('totalClaimable');

        return redirect()->route('rewards.claimed')->with('success', 'Reward claimed successfully!');
    }


    // ==========================================
    // STAFF METHODS
    // ==========================================

    /**
     * Display the reward list for STAFF.
     */
    public function staffIndex()
    {
        $rewards = Reward::latest()->paginate(10);
        return view('staff.rewards', compact('rewards'));
    }

    /**
     * Show form to create a new reward.
     */
    public function create()
    {
        return view('staff.rewards.create'); // Ensure this view exists
    }

    /**
     * Store a new reward (Staff Action).
     */
    public function store(Request $request)
    {
        // 1. TRANSLATION LAYER
        if ($request->has('name')) $request->merge(['voucherCode' => $request->name]);
        if ($request->has('points_required')) $request->merge(['rewardPoints' => $request->points_required]);
        if ($request->has('type')) $request->merge(['rewardType' => $request->type]);
        if ($request->has('value')) $request->merge(['rewardAmount' => $request->value]);
        if ($request->has('expiry_date')) $request->merge(['expiryDate' => $request->expiry_date]);

        // 2. VALIDATION
        $validated = $request->validate([
            'voucherCode'    => 'required|string|max:255',
            'rewardType'     => 'required|in:Discount,Extra Hours', 
            'rewardAmount'   => 'nullable|integer|min:1',
            'rewardPoints'   => 'required|integer|min:1',
            'totalClaimable' => 'required|integer|min:1', 
            'expiryDate'     => 'nullable|date',
            'rewardStatus'   => 'required|in:Active,Inactive',
        ]);

        // 3. GENERATE CUSTOM ID (Fixes Error 1364)
        // Find the last reward to determine the next number
        $latest = \App\Models\Reward::orderBy('rewardID', 'desc')->first();
        
        if (!$latest) {
            $newID = 'RWD001';
        } else {
            // Remove 'RWD' (3 chars), take the number, add 1
            $number = intval(substr($latest->rewardID, 3)); 
            $newID = 'RWD' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        }

        // 4. SAVE TO DATABASE
        \App\Models\Reward::create([
            'rewardID'       => $newID, // <--- Added this line
            'voucherCode'    => $validated['voucherCode'],
            'rewardType'     => $validated['rewardType'],
            'rewardAmount'   => $validated['rewardAmount'] ?? null,
            'rewardPoints'   => $validated['rewardPoints'],
            'totalClaimable' => $validated['totalClaimable'],
            'expiryDate'     => $validated['expiryDate'] ?? null,
            'rewardStatus'   => $validated['rewardStatus'],
        ]);

        return redirect()->route('staff.reward.index')
            ->with('status', 'Reward created successfully!');
    }

    /**
     * Show form to edit a reward.
     */
    public function edit($id)
    {
        $reward = Reward::findOrFail($id);
        return view('staff.rewards.edit', compact('reward')); // Ensure this view exists
    }

    /**
     * Update the specified reward in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Find the Reward
        // We use where() -> firstOrFail() to ensure it works even with custom string IDs like 'RWD001'
        $reward = \App\Models\Reward::where('rewardID', $id)->firstOrFail();

        // 2. TRANSLATION LAYER (Map Form Names -> DB Columns)
        if ($request->has('name')) $request->merge(['voucherCode' => $request->name]);
        if ($request->has('points_required')) $request->merge(['rewardPoints' => $request->points_required]);
        if ($request->has('type')) $request->merge(['rewardType' => $request->type]);
        if ($request->has('value')) $request->merge(['rewardAmount' => $request->value]);
        if ($request->has('expiry_date')) $request->merge(['expiryDate' => $request->expiry_date]);

        // 3. VALIDATION
        $validated = $request->validate([
            'voucherCode'    => 'required|string|max:255',
            'rewardType'     => 'required|in:Discount,Extra Hours',
            'rewardAmount'   => 'nullable|integer|min:1',
            'rewardPoints'   => 'required|integer|min:1',
            'totalClaimable' => 'required|integer|min:1',
            'expiryDate'     => 'nullable|date',
            'rewardStatus'   => 'required|in:Active,Inactive',
        ]);

        // 4. UPDATE DATABASE
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

    /**
     * Remove the specified reward from storage.
     */
    public function destroy($id)
    {
        // Find the reward by your custom 'rewardID' column
        $reward = \App\Models\Reward::where('rewardID', $id)->firstOrFail();
        
        $reward->delete();

        return redirect()->route('staff.reward.index')
            ->with('status', 'Reward deleted successfully!');
    }
}