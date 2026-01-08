<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Fleet;

class FleetController extends Controller
{
    public function index()
    {
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

    public function bookings($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // Use collect([]) instead of []
        $bookings = collect([]); 
        
        // If you have a relationship set up, you would use:
        // $bookings = $fleet->bookings()->orderBy('created_at', 'desc')->get();

        return view('staff.fleet.tabs.bookings', compact('fleet', 'bookings'));
    }

    public function overview($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // Use collect([]) here too if your view uses ->count() on this variable
        $availabilityCalendar = collect([]); 

        return view('staff.fleet.tabs.overview', compact('fleet', 'availabilityCalendar'));
    }

    public function maintenance($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // Fetch maintenance records using the relationship defined in your Fleet model
        // We use the relationship name 'maintenance' (singular) as defined in your Fleet.php
        $maintenances = $fleet->maintenance()->orderBy('mDate', 'desc')->get();

        return view('staff.fleet.tabs.maintenance', compact('fleet', 'maintenances'));
    }

    // 2. ADD: Method to handle the "Add Record" form submission
    public function storeMaintenance(\Illuminate\Http\Request $request, $plateNumber)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'mDate' => 'required|date',
            'mTime' => 'nullable',
            'cost' => 'required|numeric|min:0',
        ]);

        // Create the record
        // Since your Maintenance model has $incrementing = false, we generate a unique ID
        Maintenance::create([
            'maintenanceID' => 'M-' . strtoupper(uniqid()), 
            'plateNumber' => $plateNumber,
            'description' => $validated['description'],
            'mDate' => $validated['mDate'],
            'mTime' => $validated['mTime'],
            'cost' => $validated['cost'],
        ]);

        // Redirect back to the maintenance tab with a success message
        return redirect()
            ->route('staff.fleet.tabs.maintenance', $plateNumber)
            ->with('success', 'Maintenance record added successfully.');
    }

    public function owner($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        return view('staff.fleet.tabs.owner', compact('fleet'));
    }


    public function store(\Illuminate\Http\Request $request)
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

    public function update(\Illuminate\Http\Request $request, $plateNumber)
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

        return redirect()->route('staff.fleet.show', $fleet->plateNumber)->with('success', 'Vehicle updated.');
    }

    public function destroy($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        $fleet->delete();

        return redirect()->route('staff.fleet.index')->with('success', 'Vehicle deleted.');
    }

    public function show($plateNumber)
    {
        // Lookup by the Fleet model's primary key (plateNumber)
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();

        $otherFleets = Fleet::where('plateNumber', '!=', $plateNumber)
                            ->limit(3)
                            ->get();

        return view('staff.fleet.show', compact('fleet', 'otherFleets'));
    }
} 