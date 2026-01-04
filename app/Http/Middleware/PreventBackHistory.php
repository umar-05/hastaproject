<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // If this is an authenticated route and user is not authenticated, redirect to login
        if ($request->route() && $request->route()->middleware()) {
            $middlewares = $request->route()->middleware();
            if (in_array('auth:staff', $middlewares) && !Auth::guard('staff')->check()) {
                return redirect()->route('login')->withHeaders([
                    'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate, private, no-transform',
                    'Pragma' => 'no-cache',
                    'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT',
                ]);
            }
            if (in_array('auth:customer', $middlewares) && !Auth::guard('customer')->check()) {
                return redirect()->route('login')->withHeaders([
                    'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate, private, no-transform',
                    'Pragma' => 'no-cache',
                    'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT',
                ]);
            }
        }

        return $response->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate, private, no-transform',
            'Pragma' => 'no-cache',
            'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT',
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'X-Accel-Expires' => '0',
        ]);
    }
}