<?php

namespace App\Http\Controllers;

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
     */
    public function pickupReturn(): View 
    {
        $today = Carbon::today();

        // FIX: Updated to use camelCase column names (pickupDate, bookingStat)
        
        // 1. Fetch Pickups: Scheduled for TODAY
        $todayPickups = Booking::with(['fleet', 'customer'])
            ->whereDate('pickupDate', $today)
            ->whereIn('bookingStat', ['confirmed', 'pending']) 
            ->orderBy('pickupDate', 'asc') // Assuming pickupTime isn't in your migration, strictly sort by Date
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
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:staff,email'], // Check 'staff' table
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // FIX: Generate Staff ID logic using the 'staff' table
        $lastStaff = Staff::where('staffID', 'LIKE', 'STAFF%')
                          ->orderBy('staffID', 'desc')
                          ->first();

        $nextNumber = 1;
        if ($lastStaff) {
            // Extract number from STAFF001 -> 1
            $numberPart = (int) str_replace('STAFF', '', $lastStaff->staffID);
            $nextNumber = $numberPart + 1;
        }
        
        // Format: STAFF002
        $newStaffID = sprintf("STAFF%03d", $nextNumber);

        // FIX: Create directly in Staff table (removed User table logic)
        Staff::create([
            'staffID'  => $newStaffID,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Required for login
            'phoneNum' => $request->phoneNum, // Match model: phoneNum
            'position' => 'Staff',            // Default position
            
            // Add other required fields with defaults if necessary, or ensure they are nullable in DB
            // 'icNum' => '', 
        ]);

        return back()->with('status', 'Staff account created successfully! ID: ' . $newStaffID);
    }
}