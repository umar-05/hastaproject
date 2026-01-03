<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Display the staff dashboard.
     * FIX: Renamed from 'dashboard' to 'index' to match your route definition.
     */
    public function index(): View
    {
        return view('staff.dashboard');
    }

    /**
     * Display a listing of bookings.
     */
    public function bookingsIndex(): View
    {
        return view('staff.bookings.index');
    }

    /**
     * Display a listing of fleet vehicles.
     */
    public function fleetIndex(): View
    {
        return view('staff.fleet.index');
    }

    /**
     * Display a listing of maintenance records.
     */
    public function maintenanceIndex(): View
    {
        return view('staff.maintenance.index');
    }

    /**
     * Display a listing of inspections.
     */
    public function inspectionsIndex(): View
    {
        return view('staff.inspections.index');
    }

    /**
     * Display a listing of payments.
     */
    public function paymentsIndex(): View
    {
        return view('staff.payments.index');
    }

    /**
     * Display a listing of customers.
     */
    public function customersIndex(): View
    {
        return view('staff.customers.index');
    }

    /**
     * Display pickup/return page with dynamic data.
     */
    public function pickupReturn(): View 
    {
        $today = Carbon::today();
        
        // 1. Fetch Pickups: Scheduled for TODAY
        $todayPickups = Booking::with(['fleet', 'customer'])
            ->whereDate('pickupDate', $today)
            ->whereIn('bookingStat', ['confirmed', 'pending']) 
            ->orderBy('pickupDate', 'asc') 
            ->get();

        // 2. Fetch Returns: Scheduled for TODAY (or overdue)
        $pendingReturns = Booking::with(['fleet', 'customer'])
            ->whereDate('returnDate', '<=', $today) 
            ->where('bookingStat', 'active') 
            ->orderBy('returnDate', 'asc')
            ->get();

        return view('staff.pickup-return', compact('todayPickups', 'pendingReturns')); 
    }

    /**
     * Display rewards page.
     */
    public function rewards(): View
    {
        return view('staff.rewards');
    }

    /**
     * Display report page.
     */
    public function reports(): View
    {
        return view('staff.reports');
    }

    /**
     * Show the form to add a new staff member.
     */
    public function create(): View
    {
        return view('staff.add-staff');
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phoneNum' => ['required', 'numeric'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:staff,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Generate Staff ID logic
        $lastStaff = Staff::where('staffID', 'LIKE', 'STAFF%')
                          ->orderBy('staffID', 'desc')
                          ->first();

        $nextNumber = 1;
        if ($lastStaff) {
            $numberPart = (int) str_replace('STAFF', '', $lastStaff->staffID);
            $nextNumber = $numberPart + 1;
        }
        
        $newStaffID = sprintf("STAFF%03d", $nextNumber);

        Staff::create([
            'staffID'  => $newStaffID,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phoneNum' => $request->phoneNum, 
            'position' => 'Staff',            
        ]);

        return back()->with('status', 'Staff account created successfully! ID: ' . $newStaffID);
    }

    /**
     * Display the user's profile form.
     */
    public function editProfile(Request $request)
    {
        return view('staff.profile.edit', [
            'user' => Auth::guard('staff')->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        // Get the currently logged in staff first so we can use their ID in validation
        $user = Auth::guard('staff')->user();

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                // FIX: Explicitly ignore the current user's 'staffID' so they can save without "email taken" error
                Rule::unique('staff', 'email')->ignore($user->staffID, 'staffID'),
            ],
            // FIX: Changed 'phone_no' to 'phoneNum' to match your database and 'store' method
            'phoneNum' => ['nullable', 'numeric'], 
        ]);

        $user->fill($attributes);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('staff.profile.edit')->with('status', 'profile-updated');
    }
}