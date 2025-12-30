<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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
     * Display pickup/return page.
     */
    public function pickupReturn(): View
    {
        return view('staff.pickup-return');
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
     * Note: Renamed to 'reports' to match web.php route
     */
    public function reports(): View
    {
        return view('staff.reports'); // Make sure this view file exists as 'reports.blade.php' or 'report.blade.php'
    }

    /**
     * Show the form to add a new staff member.
     * Note: Renamed from 'addStaff' to 'create' to match standard Laravel conventions
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

            User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phoneNum' => $request->phoneNum,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'matric_number' => $newMatricNumber,
            'faculty' => 'Administration', 
        ]);

        return back()->with('status', 'Staff account created successfully!');
    }
}