<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'matric_number' => ['required', 'string', 'max:50', 'unique:users'],
        'faculty' => ['required', 'string', 'max:255'],
    ]);

    // create user and store in $user
    $user = User::create([
        'name' => $request->name,
        'matric_number' => $request->matric_number,
        'faculty' => $request->faculty,
    ]);

    // fire registered event
    event(new Registered($user));

    // optional: login the user immediately
    Auth::login($user);

    return redirect(route('login'))->with('success', 'You have signed up!');
}
}
