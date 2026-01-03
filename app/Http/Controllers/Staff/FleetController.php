<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Fleet;

class FleetController extends Controller
{
    public function show($id)
    {
        $fleet = Fleet::findOrFail($id);

        $otherFleets = Fleet::where('fleet_id', '!=', $id)
                            ->limit(3)
                            ->get();

        return view('staff.fleet.show', compact('fleet', 'otherFleets'));
    }
}