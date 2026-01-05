<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Mission; // Uncomment this once you create your Mission model

class MissionController extends Controller
{
    /**
     * Display a listing of the missions.
     */
    public function index()
    {
        // Example: Fetch all missions from the database
        // $missions = Mission::all(); 
        
        // Return the view (ensure resources/views/staff/mission/index.blade.php exists)
        return view('staff.mission.index');
    }

    /**
     * Show the form for creating a new mission.
     */
    public function create()
    {
        return view('staff.mission.create');
    }

    /**
     * Store a newly created mission in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // Add other fields as needed
        ]);

        // Mission::create($request->all());

        return redirect()->route('staff.mission.index')->with('success', 'Mission created successfully.');
    }

    /**
     * Show the form for editing the specified mission.
     */
    public function edit($id)
    {
        // $mission = Mission::findOrFail($id);
        return view('staff.mission.edit'); // compact('mission')
    }

    /**
     * Update the specified mission in storage.
     */
    public function update(Request $request, $id)
    {
        // Logic to update mission
        return redirect()->route('staff.mission.index')->with('success', 'Mission updated successfully.');
    }

    /**
     * Remove the specified mission from storage.
     */
    public function destroy($id)
    {
        // Logic to delete mission
        return redirect()->route('staff.mission.index')->with('success', 'Mission deleted successfully.');
    }
}