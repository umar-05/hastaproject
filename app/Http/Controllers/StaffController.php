<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Display the staff dashboard.
     */
    public function dashboard(): View
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
     * (I removed the old static version and kept this new dynamic one)
     */
    public function pickupReturn(): View 
    {
        $today = Carbon::today();

        // 1. Fetch Pickups: Scheduled for TODAY and status is 'confirmed' or 'pending'
        $todayPickups = Booking::with(['fleet', 'customer'])
            ->whereDate('pickupDate', $today)
            ->whereIn('bookingStat', ['confirmed', 'pending']) 
            ->orderBy('pickupDate', 'asc')
            ->get();

        // 2. Fetch Returns: Scheduled for TODAY (or overdue) and status is 'active'
        $pendingReturns = Booking::with(['fleet', 'customer'])
            ->whereDate('returnDate', '<=', $today)
            ->whereNotIn('bookingStat', ['cancelled', 'completed'])
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
            'name' => ['required', 'string', 'max:255'],
            'phoneNum' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Auto-generate Matric/Staff ID for Users table
        $lastStaff = User::where('matric_number', 'LIKE', 'STAFF%')
                         ->where('matric_number', 'NOT LIKE', 'STAFF-%')
                         ->orderBy('id', 'desc')
                         ->first();

        $nextNumber = 1;
        if ($lastStaff) {
            $numberPart = (int) str_replace('STAFF', '', $lastStaff->matric_number);
            $nextNumber = $numberPart + 1;
        }
        $newMatricNumber = sprintf("STAFF%03d", $nextNumber);

        // 1. Create User Record
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phoneNum' => $request->phoneNum,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'matric_number' => $newMatricNumber,
            'faculty' => 'Administration', 
        ]);

        // 2. Create Staff Record
        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phoneNum, // Map phoneNum to phone_no
            'position' => 'Staff',            // Default position
        ]);

        return back()->with('status', 'Staff account created successfully!');
    }
}