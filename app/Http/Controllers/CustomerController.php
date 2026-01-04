<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::all();
        return view('staff.customermanagement', compact('customers'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function show($matricNum) {
        $customer = Customer::where('matricNum', $matricNum)->firstOrFail();
        return view('staff.customers.show', compact('customer'));
    }

    public function edit($matricNum) {
        $customer = Customer::where('matricNum', $matricNum)->firstOrFail();
        return view('staff.customers.edit', compact('customer'));
    }

    public function destroy($matricNum) {
        Customer::where('matricNum', $matricNum)->delete();
        return redirect()->back()->with('success', 'Customer deleted successfully');
    }

    /**
     * Display the Customer Home / Dashboard page.
     * This corresponds to route('home')
     */
    public function dashboard(): View
    {
        // 1. Get the currently logged-in customer
        $customer = Auth::guard('customer')->user();

        // 2. (Optional) Fetch their "Active" booking to show on the dashboard
        // We use the camelCase 'matricNum' and 'bookingStat' as per your database
        $activeBooking = Booking::where('matricNum', $customer->matricNum)
                                ->where('bookingStat', 'active')
                                ->with('fleet') // Load car details
                                ->first();

        // 3. Return the home view with this data
        return view('home', compact('customer', 'activeBooking'));
    }

    /**
     * Display the FAQ page.
     * (This is fine to keep here as it's a static page)
     */
    public function faq(): View
    {
        return view('customer.faq');
    }

}