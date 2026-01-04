<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;
use App\Models\RewardRedemption;

class RewardController extends Controller
{
    /**
     * Show the main loyalty rewards page (customer.blade.php).
     *
     * @return \Illuminate\View\View
     */
    public function index()
{
    $user = Auth::user();

    $availableRewards = Reward::active() // Uses your scopeActive from Reward.php
        ->where('totalClaimable', '>', 0)
        // This hides rewards the user has already claimed
        ->whereDoesntHave('redemptions', function($query) use ($user) {
            $query->where('matricNum', $user->matricNum);
        })
        ->get();

    return view('reward.customer', compact('availableRewards'));
}



    /**
     * Show the user's claimed rewards.
     *
     * @return \Illuminate\View\View
     */
    public function claimed()
    {
        $customer = Auth::user();

        // Fetch rewards claimed by this user, including the reward details
        $myRewards = RewardRedemption::with('reward')
            ->where('matricNum', $customer->matricNum)
            ->get();

        return view('reward.claimed', compact('myRewards'));
    }

    /**
     * (Optional) API endpoint to save claimed rewards to DB
     * — useful if you later want to persist beyond localStorage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

public function claim(Request $request)
{
    $request->validate(['rewardID' => 'required|exists:reward,rewardID']);
    
    $reward = Reward::where('rewardID', $request->rewardID)->first();
    $customer = Auth::user();

    // 2. Logic: Ensure user has enough stamps
    if ($customer->stamps < $reward->rewardPoints) {
        return back()->with('error', 'Not enough stamps!');
    }

    // 3. Create the Database Link
    \App\Models\Reward::create([
        'matricNum' => $customer->matricNum,
        'rewardID' => $reward->rewardID,
        'redemptionDate' => now(),
    ]);

    // 4. Update the Reward table (reduce slots)
    $reward->decrement('totalClaimable');

    return redirect()->route('reward.claimed')->with('success', 'Reward claimed successfully!');
}

    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            // CHANGE: 'unique:rewards' -> 'unique:reward'
            'voucherCode'    => 'required|string|unique:reward,voucherCode', 
            'rewardType'     => 'required|string',
            'rewardAmount'   => 'required|numeric|min:1',
            'rewardPoints'   => 'required|integer|min:1',
            'totalClaimable' => 'required|integer|min:1',
            'expiryDate'     => 'required|date',
            'rewardStatus'   => 'required|string',
        ]);

        // 2. Generate a manual ID (since rewardID is not auto-incrementing)
        $rewardID = 'REW-' . strtoupper(\Illuminate\Support\Str::random(6));

        // 3. Save to Database
        \App\Models\Reward::create([
            'rewardID'       => $rewardID,
            'voucherCode'    => strtoupper($validated['voucherCode']),
            'rewardType'     => $validated['rewardType'],
            'rewardAmount'   => $validated['rewardAmount'],
            'rewardPoints'   => $validated['rewardPoints'],
            'totalClaimable' => $validated['totalClaimable'],
            'expiryDate'     => $validated['expiryDate'],
            'rewardStatus'   => $validated['rewardStatus'],
        ]);

        // 4. Redirect
        return redirect()->route('staff.rewards')->with('success', 'Reward created successfully!');
    }

    
}
