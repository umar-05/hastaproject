<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklist
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customer = Auth::guard('customer')->user();

        // Check if customer is logged in AND their status starts with 'blacklisted'
        if ($customer && str_starts_with($customer->accStatus, 'blacklisted')) {
            // Block access and display the message
            abort(403, "You are blacklisted. Please send an appeal at support@hasta.com");
        }

        return $next($request);
    }
}