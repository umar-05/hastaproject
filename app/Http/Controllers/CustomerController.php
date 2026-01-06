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
    // ... (keep your existing index, create, show, edit, destroy methods) ...
    public function index() {
        $customers = Customer::all();
        return view('staff.customermanagement', compact('customers'));
    }

    public function create() { return view('staff.create'); }

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
     */
    public function dashboard(): View
    {
        // 1. Get logged-in customer
        $customer = Auth::guard('customer')->user();

        // 2. Fetch Active Booking
        $activeBooking = null;
        if ($customer) {
            $activeBooking = Booking::where('matricNum', $customer->matricNum)
                                    ->where('bookingStat', 'active') // Ensure this matches DB value (Active/active)
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
                                     
                                     // --- Image Logic ---
                                     // Check if 'photos' column has data, otherwise fallback
                                     if (!empty($fleet->photos)) {
                                         $image = $fleet->photos;
                                     } else {
                                         $image = 'default-car.png'; // Default fallback
                                         if (str_contains($model, 'axia')) $image = ($year >= 2023) ? 'axia-2024.png' : 'axia-2018.png';
                                         elseif (str_contains($model, 'bezza')) $image = 'bezza-2018.png';
                                         elseif (str_contains($model, 'myvi')) $image = ($year >= 2020) ? 'myvi-2020.png' : 'myvi-2015.png';
                                         elseif (str_contains($model, 'saga')) $image = 'saga-2017.png';
                                         elseif (str_contains($model, 'alza')) $image = 'alza-2019.png';
                                         elseif (str_contains($model, 'aruz')) $image = 'aruz-2020.png';
                                         elseif (str_contains($model, 'vellfire')) $image = 'vellfire-2020.png';
                                         elseif (str_contains($model, 'x50')) $image = 'x50-2024.png';
                                         elseif (str_contains($model, 'y15')) $image = 'y15zr-2023.png';
                                     }

                                     // --- Price Logic ---
                                     $price = 150; 
                                     if (str_contains($model, 'axia')) $price = 120;
                                     elseif (str_contains($model, 'bezza')) $price = 140;
                                     elseif (str_contains($model, 'myvi')) $price = 130;
                                     elseif (str_contains($model, 'alza')) $price = 200;
                                     elseif (str_contains($model, 'vellfire')) $price = 500;
                                     elseif (str_contains($model, 'y15')) $price = 50;

                                     // --- Type Logic ---
                                     $type = 'Car';
                                     if (str_contains($model, 'axia') || str_contains($model, 'myvi')) $type = 'Hatchback';
                                     elseif (str_contains($model, 'bezza') || str_contains($model, 'saga')) $type = 'Sedan';
                                     elseif (str_contains($model, 'alza') || str_contains($model, 'vellfire')) $type = 'MPV';
                                     elseif (str_contains($model, 'aruz') || str_contains($model, 'x50')) $type = 'SUV';
                                     elseif (str_contains($model, 'y15')) $type = 'Motorcycle';

                                     return (object) [
                                         'plateNumber' => $fleet->plateNumber,
                                         'name' => $fleet->modelName, // Removed year concatenation to match style
                                         'type' => $type,
                                         'price' => $price,
                                         'image' => $image,
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