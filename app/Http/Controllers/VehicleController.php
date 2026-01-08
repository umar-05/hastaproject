<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fleet;

class VehicleController extends Controller
{
    public function welcome()
    {
        $featuredVehicles = Fleet::latest()
                                 ->take(3)
                                 ->get()
                                 ->map(function ($fleet) {
                                     $data = $this->formatVehicleData($fleet);
                                     return (object) $data;
                                 });

        return view('home', [
            'featuredVehicles' => $featuredVehicles,
            'activeBooking' => null 
        ]);
    }

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

    private function formatVehicleData($fleet)
    {
        $specs = $this->resolveSpecs($fleet->modelName, $fleet->year);

        return [
            'id'           => $fleet->plateNumber,
            'plateNumber'  => $fleet->plateNumber,
            'name'         => $fleet->modelName . ' ' . $fleet->year,
            // keep original model name for views that expect this property
            'modelName'    => $fleet->modelName,
            'type'         => $specs['type'],
            
            // legacy/view-friendly price key
            'price'        => $fleet->price,
            'pricePerDay'  => $fleet->price,

            'image'        => $fleet->photo1 ?? $specs['image'], 
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
            'axia'     => ['type' => 'Hatchback', 'image' => 'axia-2018.png', 'seats' => 5, 'luggage' => 2],
            'bezza'    => ['type' => 'Sedan',     'image' => 'bezza-2018.png', 'seats' => 5, 'luggage' => 3],
            'myvi'     => ['type' => 'Hatchback', 'image' => 'myvi-2015.png', 'seats' => 5, 'luggage' => 2],
            'saga'     => ['type' => 'Sedan',     'image' => 'saga-2017.png', 'seats' => 5, 'luggage' => 3],
            'alza'     => ['type' => 'MPV',       'image' => 'alza-2019.png', 'seats' => 7, 'luggage' => 4],
            'aruz'     => ['type' => 'SUV',       'image' => 'aruz-2020.png', 'seats' => 7, 'luggage' => 5],
            'vellfire' => ['type' => 'MPV',       'image' => 'vellfire-2020.png','seats' => 7, 'luggage' => 6],
            'x50'      => ['type' => 'SUV',       'image' => 'x50-2024.png', 'seats' => 5, 'luggage' => 4],
            'y15'      => ['type' => 'Motorcycle','image' => 'y15zr-2023.png','seats' => 2, 'luggage' => 0],
        ];

        if (str_contains($model, 'myvi') && $year >= 2020) {
            $configs['myvi']['image'] = 'myvi-2020.png';
        }
        if (str_contains($model, 'axia') && $year >= 2023) {
            $configs['axia']['image'] = 'axia-2024.png';
        }

        foreach ($configs as $key => $config) {
            if (str_contains($model, $key)) {
                return $config;
            }
        }

        // Fallback
        return [
            'type'    => 'Sedan',
            'image'   => 'default-car.png',
            'seats'   => 5,
            'luggage' => 2
        ];
    }
}