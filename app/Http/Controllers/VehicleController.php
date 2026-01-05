<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fleet;

class VehicleController extends Controller
{
    /**
     * Display the Public Welcome/Home Page with Featured Vehicles
     */
    public function welcome()
    {
        // Fetch 3 latest available vehicles
        $featuredVehicles = Fleet::latest()
                                 ->take(3)
                                 ->get()
                                 ->map(function ($fleet) {
                                     // Reuse the existing formatting logic
                                     $data = $this->formatVehicleData($fleet);
                                     // Convert array to Object to match home.blade.php syntax ($vehicle->name)
                                     return (object) $data;
                                 });

        // Pass null for activeBooking since guests don't have one
        return view('home', [
            'featuredVehicles' => $featuredVehicles,
            'activeBooking' => null 
        ]);
    }

    /**
     * Display a listing of the vehicles.
     */
    public function index()
    {
        $fleets = Fleet::where('status', 'available')
                       ->orderBy('modelName')
                       ->get();

        $vehicles = $fleets->map(function ($fleet) {
            return $this->formatVehicleData($fleet);
        });

        return view('vehicles.index', compact('vehicles'));
    }

    public function show($id)
    {
        $fleet = Fleet::where('plateNumber', $id)->firstOrFail();
        $vehicle = $this->formatVehicleData($fleet);
        return view('vehicles.show', compact('vehicle'));
    }

    public function bookNow()
    {
        return $this->index();
    }

    /**
     * Helper: Format Fleet model into a standardized array.
     */
    private function formatVehicleData($fleet)
    {
        $specs = $this->resolveSpecs($fleet->modelName, $fleet->year);

        return [
            'id'           => $fleet->plateNumber,
            'plateNumber'  => $fleet->plateNumber, // Ensure this exists for the route key
            'name'         => $fleet->modelName . ' ' . $fleet->year,
            'type'         => $specs['type'],
            'price'        => $specs['price'],
            'image'        => $fleet->photos ?? $specs['image'],
            'transmission' => 'Automatic',
            'fuel'         => 'RON 95',
            'ac'           => true,
            'seats'        => $specs['seats'],
            'luggage'      => $specs['luggage'],
            'description'  => $fleet->note ?? "Enjoy a smooth ride with our {$fleet->modelName}.",
        ];
    }

    private function resolveSpecs($modelName, $year)
    {
        $model = strtolower($modelName);
        
        $configs = [
            'axia'     => ['type' => 'Hatchback', 'price' => 120, 'image' => 'axia-2018.png', 'seats' => 5, 'luggage' => 2],
            'bezza'    => ['type' => 'Sedan',     'price' => 140, 'image' => 'bezza-2018.png', 'seats' => 5, 'luggage' => 3],
            'myvi'     => ['type' => 'Hatchback', 'price' => 130, 'image' => 'myvi-2015.png', 'seats' => 5, 'luggage' => 2],
            'saga'     => ['type' => 'Sedan',     'price' => 120, 'image' => 'saga-2017.png', 'seats' => 5, 'luggage' => 3],
            'alza'     => ['type' => 'MPV',       'price' => 200, 'image' => 'alza-2019.png', 'seats' => 7, 'luggage' => 4],
            'aruz'     => ['type' => 'SUV',       'price' => 180, 'image' => 'aruz-2020.png', 'seats' => 7, 'luggage' => 5],
            'vellfire' => ['type' => 'MPV',       'price' => 500, 'image' => 'vellfire-2020.png','seats' => 7, 'luggage' => 6],
            'x50'      => ['type' => 'SUV',       'price' => 250, 'image' => 'x50-2024.png', 'seats' => 5, 'luggage' => 4],
            'y15'      => ['type' => 'Motorcycle','price' => 50,  'image' => 'y15zr-2023.png','seats' => 2, 'luggage' => 0],
        ];

        if (str_contains($model, 'myvi') && $year >= 2020) {
            $configs['myvi']['price'] = 150;
            $configs['myvi']['image'] = 'myvi-2020.png';
        }
        if (str_contains($model, 'axia') && $year >= 2023) {
            $configs['axia']['price'] = 130;
            $configs['axia']['image'] = 'axia-2024.png';
        }

        foreach ($configs as $key => $config) {
            if (str_contains($model, $key)) {
                return $config;
            }
        }

        return [
            'type'    => 'Sedan',
            'price'   => 150,
            'image'   => 'default-car.png',
            'seats'   => 5,
            'luggage' => 2
        ];
    }
}