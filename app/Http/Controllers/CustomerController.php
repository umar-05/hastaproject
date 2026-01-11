<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Fleet;

class CustomerController extends Controller
{
    public function index()
{
    $customers = \App\Models\Customer::all();
    return view('staff.customermanagement', compact('customers'));
}

    public function create() { return view('staff.create'); }

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
     */
    public function dashboard(): View
    {
        // 1. Get logged-in customer
        $customer = Auth::guard('customer')->user();

        // 2. Fetch Active Booking
        $activeBooking = null;
        if ($customer) {
            $activeBooking = Booking::where('matricNum', $customer->matricNum)
                                    ->where('bookingStat', 'active') 
                                    ->with('fleet')
                                    ->first();
        }

        // 3. Fetch Featured Vehicles (Fetch ANY 3 latest vehicles)
        $featuredVehicles = Fleet::latest()
                                 ->take(3)
                                 ->get()
                                 ->map(function ($fleet) {
                                     $model = strtolower($fleet->modelName);
                                     $year = $fleet->year;
                                     
                                      

                                     // --- Price Logic (UPDATED) ---
                                     $price = $fleet->price;

                                     // --- Type Logic ---
                                     $type = 'Car';
                                     if (str_contains($model, 'axia') || str_contains($model, 'myvi')) $type = 'Hatchback';
                                     elseif (str_contains($model, 'bezza') || str_contains($model, 'saga')) $type = 'Sedan';
                                     elseif (str_contains($model, 'alza') || str_contains($model, 'vellfire')) $type = 'MPV';
                                     elseif (str_contains($model, 'aruz') || str_contains($model, 'x50')) $type = 'SUV';
                                     elseif (str_contains($model, 'y15')) $type = 'Motorcycle';

                                     return (object) [
                                         'plateNumber' => $fleet->plateNumber,
                                         'name' => $fleet->modelName, 
                                         'type' => $type,
                                         'price' => $price,
                                         'image' => $fleet->photo1,
                                         'seats' => str_contains($type, 'Motorcycle') ? 2 : 5,
                                         'transmission' => str_contains($type, 'Motorcycle') ? 'Manual' : 'Auto'
                                     ];
                                 });

        return view('home', compact('customer', 'activeBooking', 'featuredVehicles'));
    }

    public function faq(): View
    {
        return view('customer.faq');
    }
}