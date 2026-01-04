<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
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