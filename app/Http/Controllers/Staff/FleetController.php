<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Fleet;
use Illuminate\Http\Request;

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

        $validated = $request->validate([
            'modelName' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100',
            'status' => 'nullable|string',
        ]);

        $fleet->update([
            'modelName' => $validated['modelName'],
            'year' => $validated['year'],
            'status' => $validated['status'] ?? $fleet->status,
        ]);

        // Redirect back to the show (details) page
        return redirect()->route('staff.fleet.show', $fleet->plateNumber)->with('success', 'Vehicle updated.');
    }

    public function destroy($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        $fleet->delete();

        return redirect()->route('staff.fleet.index')->with('success', 'Vehicle deleted.');
    }

    // In app/Http/Controllers/Staff/FleetController.php

public function show($plateNumber)
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
}
}