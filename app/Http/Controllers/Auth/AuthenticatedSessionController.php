<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 1. Try logging in as CUSTOMER
        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        // 2. Try logging in as STAFF
        if (Auth::guard('staff')->attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('staff.dashboard'));
        }

        // 3. If both fail
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Simple logout for all guards
        Auth::guard('staff')->logout();
        Auth::guard('customer')->logout();
        Auth::guard('web')->logout();

        // Clear session completely
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Force redirect to login
        return redirect('/login');
    }
}