<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Fleet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 

class FleetController extends Controller
{
    public function index()
    {
        // Renamed variable to $fleets for clarity, though keeping $fleet is fine if view expects it
        $fleet = Fleet::orderBy('created_at', 'desc')->paginate(12);
        $totalVehicles = Fleet::count();
        $availableCount = Fleet::where('status', 'available')->count();
        $rentedCount = Fleet::whereIn('status', ['booked', 'rented'])->count();
        $maintenanceCount = Fleet::where('status', 'maintenance')->count();

        return view('staff.fleet.index', compact('fleet', 'totalVehicles', 'availableCount', 'rentedCount', 'maintenanceCount'));
    }

    public function create()
    {
        // Return a simple form for adding a vehicle
        return view('staff.fleet.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plateNumber' => 'required|string|unique:fleet,plateNumber',
            'modelName' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100',
            'status' => 'nullable|string',
            'color'      => 'nullable|string|max:50',
        ]);

        Fleet::create([
            'plateNumber' => $validated['plateNumber'],
            'modelName' => $validated['modelName'],
            'year' => $validated['year'],
            'status' => $validated['status'] ?? 'available',
        ]);

        return redirect()->route('staff.fleet.index')->with('success', 'Vehicle added.');
    }

    public function edit($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        return view('staff.fleet.edit', compact('fleet'));
    }

    public function update(Request $request, $plateNumber)
{
    $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();

    // 1. VALIDATION
    $validated = $request->validate([
        'modelName'  => 'required|string|max:255',
        'year'       => 'required|integer|min:1900|max:2100',
        'status'     => 'nullable|string',
        'color'     => 'nullable|string|max:255',
        'ownerIC'    => 'required|string', 
        'ownerName'  => 'nullable|string|max:255',
        
        // FIX: Check for unique phone, ignoring the current ownerIC
        'ownerPhone' => [
            'nullable', 
            'string', 
            'max:255',
            Rule::unique('owner', 'ownerPhoneNum')->ignore($request->ownerIC, 'ownerIC')
        ],
        
        // FIX: Check for unique email, ignoring the current ownerIC
        'ownerEmail' => [
            'nullable', 
            'email', 
            'max:255',
            Rule::unique('owner', 'ownerEmail')->ignore($request->ownerIC, 'ownerIC')
        ],
    ]);

    // 2. UPDATE/CREATE OWNER (Do this first to ensure ID exists)
    \App\Models\Owner::updateOrCreate(
        ['ownerIC' => $validated['ownerIC']], 
        [
            'ownerName'     => $validated['ownerName'],
            'ownerPhoneNum' => $validated['ownerPhone'], // Maps input 'ownerPhone' to DB 'ownerPhoneNum'
            'ownerEmail'    => $validated['ownerEmail'],
        ]
    );

    // 3. UPDATE FLEET (Link vehicle to owner)
    $fleet->update([
        'modelName' => $validated['modelName'],
        'year'      => $validated['year'],
        'color'     => $validated['color'],
        'status'    => $validated['status'] ?? $fleet->status,
        'ownerIC'   => $validated['ownerIC'],
    ]);

    // 4. REDIRECT
    if ($request->has('from_owner_tab')) {
        return redirect()->route('staff.fleet.tabs.owner', $fleet->plateNumber)
                         ->with('success', 'Owner information updated.');
    }

    return redirect()->route('staff.fleet.show', $fleet->plateNumber)
                     ->with('success', 'Vehicle updated.');
}
    public function destroy($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        $fleet->delete();

        return redirect()->route('staff.fleet.index')->with('success', 'Vehicle deleted.');
    }

    // In app/Http/Controllers/Staff/FleetController.php

/*public function show($plateNumber)
{
    $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();

    // 1. Fetch Relationships
    $bookings = $fleet->bookings()->orderBy('pickupDate', 'desc')->get();
    $maintenances = $fleet->maintenances()->orderBy('mDate', 'desc')->get();

    // 2. Generate Availability Calendar Data
    $availabilityCalendar = [];
    $startOfMonth = now()->startOfMonth();
    $endOfMonth = now()->endOfMonth();

    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
        $dateStr = $date->format('Y-m-d');
        $status = 'available';

        // Check Bookings
        foreach ($bookings as $booking) {
            // Assuming booking has pickupDate/returnDate and status
            if ($booking->bookingStat !== 'cancelled' && 
                $date->between($booking->pickupDate, $booking->returnDate)) {
                $status = 'booked';
                break;
            }
        }

        // Check Maintenance
        if ($status === 'available') {
            foreach ($maintenances as $maintenance) {
                // Assuming maintenance has mDate and maybe an endDate? 
                // If only single date, check equality.
                if ($maintenance->mDate && $date->isSameDay($maintenance->mDate)) {
                    $status = 'maintenance';
                    break;
                }
            }
        }

        $availabilityCalendar[$dateStr] = ['status' => $status];
    }

    return view('staff.fleet.showdetails', [
        'vehicle' => $fleet,
        'fleet' => $fleet,
        'bookings' => $bookings,
        'maintenances' => $maintenances,
        'availabilityCalendar' => $availabilityCalendar
    ]);
}*/

// 1. MAIN ENTRY: Redirects to the first tab (Overview)
    public function show($plateNumber)
    {
        return redirect()->route('staff.fleet.tabs.overview', $plateNumber);
    }

    // 2. OVERVIEW TAB: Handles the Calendar Calculation
    public function overview($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();

        // fetch data needed ONLY for the calculation logic
        $bookings = $fleet->bookings()->get();
        $maintenances = $fleet->maintenances()->get();

        // --- START: YOUR CALENDAR LOGIC (Moved here) ---
        $availabilityCalendar = [];
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $status = 'available';

            // Check Bookings
            foreach ($bookings as $booking) {
                if ($booking->bookingStat !== 'cancelled' && 
                    $date->between($booking->pickupDate, $booking->returnDate)) {
                    $status = 'booked';
                    break;
                }
            }

            // Check Maintenance
            if ($status === 'available') {
                foreach ($maintenances as $maintenance) {
                    if ($maintenance->mDate && $date->isSameDay($maintenance->mDate)) {
                        $status = 'maintenance';
                        break;
                    }
                }
            }

            $availabilityCalendar[$dateStr] = ['status' => $status];
        }
        // --- END: YOUR CALENDAR LOGIC ---

        return view('staff.fleet.tabs.overview', compact('fleet', 'availabilityCalendar'));
    }

    // 3. BOOKINGS TAB: Just fetches history
    public function bookings($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // Your specific sorting logic
        $bookings = $fleet->bookings()->orderBy('pickupDate', 'desc')->get();

        return view('staff.fleet.tabs.bookings', compact('fleet', 'bookings'));
    }

    // 4. MAINTENANCE TAB: Just fetches logs
    public function maintenance($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // Your specific sorting logic
        $maintenances = $fleet->maintenances()->orderBy('mDate', 'desc')->get();

        return view('staff.fleet.tabs.maintenance', compact('fleet', 'maintenances'));
    }
    public function storeMaintenance(Request $request, $plateNumber)
    {
        // 1. Validate the form inputs
        $validated = $request->validate([
            'description' => 'required|string|max:500',
            'mDate'       => 'required|date',
            'mTime'       => 'nullable', // Time is optional
            'cost'        => 'required|numeric|min:0',
        ]);

        // 2. Create the record
        \App\Models\Maintenance::create([
            'plateNumber' => $plateNumber,
            'description' => $validated['description'],
            'mDate'       => $validated['mDate'],
            'mTime'       => $validated['mTime'],
            'cost'        => $validated['cost'],
            // maintenanceID is handled automatically by the Model's boot method
        ]);

        // 3. Redirect back to the Maintenance tab
        return redirect()->route('staff.fleet.tabs.maintenance', $plateNumber)
                         ->with('success', 'Maintenance log added successfully.');
    }

    // 5. DOCUMENTS TAB (New)
    public function documents($plateNumber)
    {
        // Load owner for the sidebar details usually shown here
        $fleet = Fleet::with('owner')->where('plateNumber', $plateNumber)->firstOrFail();
        
        return view('staff.fleet.tabs.owner', compact('fleet'));
    }
}