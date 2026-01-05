<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
{
    $customers = \App\Models\Customer::all();
    return view('staff.customermanagement', compact('customers'));
}

    public function create()
    {
        return view('staff.create');
    }

    public function edit($matricNum) 
    {
        $customer = \App\Models\Customer::where('matricNum', $matricNum)->firstOrFail();
        return view('staff.editcustomermanagement', compact('customer'));
    }

    public function update(Request $request, $matricNum)
    {
    $customer = \App\Models\Customer::where('matricNum', $matricNum)->firstOrFail();

        // 1. Validate using your specific DB attributes
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icNum_passport' => 'required|string',
            'email' => 'required|email',
            'phoneNum' => 'required|string',
            'collegeAddress' => 'required|string',
        ]);
        // 2. Update the record
        $customer->update($validated);
        // 3. Redirect back with success message
        return redirect('/staff/customermanagement-crud')
            ->with('success', 'Customer record updated successfully!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
                'matricNum'       => 'required|string|unique:customer,matricNum',
                'name'            => 'required|string|max:255',
                'ic_number'       => 'required|string', // Match name="ic_number" in form
                'email'           => 'required|email|unique:customer,email',
                'phone'           => 'required|string', // Match name="phone" in form
                'college_address' => 'required|string',
            ]);

            \App\Models\Customer::create($validated);

            return redirect()->route('staff.customermanagement-crud.index')
                            ->with('success', 'Customer added successfully!');
    }

    public function destroy($matricNum) {
        Customer::where('matricNum', $matricNum)->delete();
        return redirect()->back()->with('success', 'Customer deleted successfully');
    }

    public function show($matricNum)
    {
    $customer = \App\Models\Customer::where('matricNum', $matricNum)->firstOrFail();
        return response()->json($customer);
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