<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;

class RewardController extends Controller
{
    /**
     * Show the main loyalty rewards page (customer.blade.php).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
    // Fetch data for the staff dashboard tables
    $activeRewards = Reward::where('rewardStatus', 'Active')->get();
    $inactiveRewards = Reward::where('rewardStatus', 'Inactive')->get();

    // Calculate stats for the summary cards
    $stats = [
        'total' => Reward::count(),
        'active' => $activeRewards->count(),
        'slots' => $activeRewards->sum('totalClaimable'),
    ];

    // CHANGE: Return the staff view, not the customer one
    return view('staff.rewards', compact('activeRewards', 'inactiveRewards', 'stats'));
    }

    /**
     * Show the user's claimed rewards.
     *
     * @return \Illuminate\View\View
     */
    public function claimed()
    {
        return view('reward.claimed');
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
        $request->validate([
            'code' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        return response()->json(['success' => true, 'message' => 'Reward claimed!']);
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
