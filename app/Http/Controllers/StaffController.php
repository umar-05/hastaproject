<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

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
     */
    public function report(): View
    {
        return view('staff.report');
    }

    /**
     * Display add staff page.
     */
    public function addStaff(): View
    {
        return view('staff.add-staff');
    }
}
