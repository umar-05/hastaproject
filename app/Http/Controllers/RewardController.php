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
        return view('reward.customer');
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
}
