<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Booking; 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;

class StaffController extends Controller
{
    /**
     * Display the Staff Dashboard.
     */
    public function index(): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $bookingsToManage = Booking::where('bookingStat', 'Pending')->count();
        $pickupsToday = Booking::whereDate('pickupDate', now())->where('bookingStat', 'Confirmed')->count();
        $returnsToday = Booking::whereDate('returnDate', now())->where('bookingStat', 'Active')->count();

        return view('staff.dashboard', [
            'bookingsToManage' => $bookingsToManage,
            'pickupsToday' => $pickupsToday,
            'returnsToday' => $returnsToday,
        ]);
    }

    /**
     * Display the Pickup & Return Inspection page.
     */
    public function pickupReturn(): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $todayPickups = Booking::with(['customer', 'fleet'])
            ->whereDate('pickupDate', now())
            ->where('bookingStat', 'Confirmed')
            ->orderBy('pickupDate')
            ->get();

        $pendingReturns = Booking::with(['customer', 'fleet'])
            ->where('bookingStat', 'Active') 
            ->orderBy('returnDate')
            ->get();

        return view('staff.pickup-return', [
            'todayPickups' => $todayPickups,
            'pendingReturns' => $pendingReturns,
        ]);
    }

    /**
     * Show the profile edit form.
     */
    public function editProfile(Request $request): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        return view('staff.profile.edit', [
            'user' => $request->user('staff'),
        ]);
    }

    /**
     * Update the staff's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('staff')->ignore($request->user('staff')->staffID, 'staffID')],
            'position' => ['nullable', 'string', 'max:255'],
            'phoneNum' => ['nullable', 'string', 'max:20'],
            'icNum_passport' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'max:100'],
            'eme_name' => ['nullable', 'string', 'max:255'],
            'emerelation' => ['nullable', 'string', 'max:100'],
            'emephoneNum' => ['nullable', 'string', 'max:20'],
            'bankName' => ['nullable', 'string', 'max:100'],
            'accountNum' => ['nullable', 'string', 'max:50'],
        ]);

        $user = $request->user('staff');
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('staff.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Show form to add new staff.
     */
    public function create(): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

    $staffs = Staff::all();

    // 3. Send the data to the page
    return view('staff.add-staff', [
        'staffs' => $staffs, // This fills the table
        'totalStaffCount' => $staffs->count(), // Fills Card 1
        'driverCount' => $staffs->where('position', 'Driver')->count(), // Fills Card 2
        'adminCount' => $staffs->where('position', 'Administrator')->count(), // Fills Card 3
        'managerCount' => $staffs->where('position', 'Manager')->count(), // Fills Card 4
    ]);

        // FIX: Changed from 'staff.add' to 'staff.add-staff'
        return view('staff.add-staff'); 
    }

    /**
     * Store a new staff member.
     */
    public function createFunctioning() 
{
    // Check if staff is logged in
    if (!auth()->guard('staff')->check()) {
        return redirect()->route('login');
    }

    // This looks for the file: resources/views/staff/add-stafffunctioning.blade.php
    return view('staff.add-stafffunctioning');
}

    public function store(Request $request): RedirectResponse
{
    // 1. Ensure user is authenticated
    if (!Auth::guard('staff')->check()) {
        return redirect()->route('login');
    }

    // 2. Pre-process Email
    // Combine the username input with the hardcoded domain
    $fullEmail = $request->input('email_username') . '@hasta.com';

    // Merge this full email back into the request data so we can validate 'email'
    $request->merge(['email' => $fullEmail]);

    // 3. Validate
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email_username' => ['required', 'string', 'max:50', 'alpha_dash'], // Ensure username has no spaces/weird chars
        'email' => ['required', 'string', 'email', 'max:255', 'unique:staff'], // Validate the FULL email
        'position' => ['required', 'string', 'max:50'],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
    ]);

    // 4. Auto-Generate Staff ID
    $latestStaff = Staff::orderBy('staffID', 'desc')->first();
    
    if (!$latestStaff) {
        $newStaffID = 'STAFF001';
    } else {
        // Extract the number part from 'STAFF005'
        // 'STAFF' is 5 characters long, so we substring from index 5
        $lastNumber = intval(substr($latestStaff->staffID, 5)); 
        $newStaffID = 'STAFF' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    // 5. Create Staff
    Staff::create([
        'name' => $request->name,
        'staffID' => $newStaffID,
        'email' => $request->email, // This now contains 'username@hasta.com'
        'position' => $request->position,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('staff.add-stafffunctioning')
    ->with('status', 'Staff member ' . $newStaffID . ' added successfully!');
}

    /**
     * Show reports page.
     */
    public function reports(): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        return view('staff.report'); 
    }

    /**
     * Bookings management list for staff
     */
    public function bookingManagement(Request $request): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $bookings = Booking::with(['customer', 'fleet', 'reward'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Compute dashboard metrics (case-insensitive status checks)
        $totalBookings = Booking::count();
        $confirmedCount = Booking::whereRaw('LOWER(bookingStat) = ?', ['confirmed'])->count();
        $pendingCount = Booking::whereRaw('LOWER(bookingStat) = ?', ['pending'])->count();
        $completedCount = Booking::whereRaw('LOWER(bookingStat) = ?', ['completed'])->count();

        // Pending verification: if the `payment_status` column exists, use it;
        // otherwise fall back to counting pending bookings.
        if (Schema::hasColumn('booking', 'payment_status')) {
            $pendingVerificationCount = Booking::whereRaw('LOWER(bookingStat) = ?', ['pending'])
                ->where(function($q) {
                    $q->whereNull('payment_status')
                      ->orWhereRaw('LOWER(payment_status) <> ?', ['paid']);
                })->count();
        } else {
            $pendingVerificationCount = Booking::whereRaw('LOWER(bookingStat) = ?', ['pending'])->count();
        }

        return view('staff.bookingmanagement', [
            'bookings' => $bookings,
            'totalBookings' => $totalBookings,
            'confirmedCount' => $confirmedCount,
            'pendingCount' => $pendingCount,
            'pendingVerificationCount' => $pendingVerificationCount,
            'completedCount' => $completedCount,
        ]);
    }
}