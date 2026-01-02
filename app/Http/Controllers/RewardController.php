<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    /**
     * Show the main loyalty rewards page (customer.blade.php).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // In real app: fetch user's stamps from DB
        // For demo, we'll pass dummy data or let frontend handle it
        return view('rewards.customer');
    }

    /**
     * Show the user's claimed rewards.
     *
     * @return \Illuminate\View\View
     */
    public function showClaimed()
    {
        // In a real app, you'd fetch from the database:
        // $claimedRewards = Auth::user()->claimedRewards;

        // For now, we'll pass an empty array — the frontend will load from localStorage
        // (or later you can replace this with real DB data)

        return view('rewards.my-rewards');
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

        // Example: Save to claimed_rewards table
        // Auth::user()->claimedRewards()->create($request->only('code', 'title', 'description'));

        return response()->json(['success' => true, 'message' => 'Reward claimed!']);
    }
}
