<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        // Ensure the correct guard is used for this request so Auth::user() returns the right model.
        if (Auth::guard('staff')->check()) {
            Auth::shouldUse('staff');
            return redirect()->intended(route('home', absolute: false));
        }

        if (Auth::check()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Fallback: not authenticated (shouldn't normally happen because LoginRequest throws),
        // return back with an error so we don't land on a protected page as guest.
        return back()->withErrors(['email' => trans('auth.failed')]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout both guards to be safe
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
