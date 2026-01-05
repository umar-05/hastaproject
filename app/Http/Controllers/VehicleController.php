<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fleet;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index()
    {
        // Fetch all available vehicles from the database
        // You can change 'available' to whatever status logic you prefer
        $fleets = Fleet::where('status', 'available')
                       ->orderBy('modelName')
                       ->get();

        // Transform the database results into the format expected by the view
        $vehicles = $fleets->map(function ($fleet) {
            return $this->formatVehicleData($fleet);
        });

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Display the specified vehicle.
     */
    public function show($id)
    {
        // Find the vehicle by Plate Number (Primary Key) or fail with 404
        $fleet = Fleet::where('plateNumber', $id)->firstOrFail();

        // Format data for the view
        $vehicle = $this->formatVehicleData($fleet);

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Handle the "Book Now" logic (Customer facing).
     */
    public function bookNow()
    {
        // Reuse the index logic or apply specific filters for booking
        return $this->index();
    }

    /**
     * Helper: Format Fleet model into a standardized array for views.
     * This allows us to centralize the logic for Price, Type, and Image.
     */
    private function formatVehicleData($fleet)
    {
        // Resolve dynamic specs (Price, Type, Image) based on model name
        $specs = $this->resolveSpecs($fleet->modelName, $fleet->year);

        return [
            'id'           => $fleet->plateNumber,
            'name'         => $fleet->modelName . ' ' . $fleet->year,
            'plateNumber'  => $fleet->plateNumber,
            'type'         => $specs['type'],
            'price'        => $specs['price'],
            
            // Use DB photo if exists, otherwise fallback to mapped image
            'image'        => $fleet->photos ?? $specs['image'],
            
            // Default attributes (can be moved to DB columns later)
            'transmission' => 'Automatic',
            'fuel'         => 'RON 95',
            'ac'           => true,
            'seats'        => $specs['seats'],
            'luggage'      => $specs['luggage'],
            'description'  => $fleet->note ?? "Enjoy a smooth ride with our {$fleet->modelName}. Perfect for your journey.",
        ];
    }

    /**
     * Helper: Resolve vehicle specifications based on Model Name.
     * This acts as a flexible configuration instead of hardcoded if-else chains.
     */
    private function resolveSpecs($modelName, $year)
    {
        $model = strtolower($modelName);

        // flexible configuration for car models
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

        // Specific overrides based on Year
        if (str_contains($model, 'myvi') && $year >= 2020) {
            $configs['myvi']['price'] = 150;
            $configs['myvi']['image'] = 'myvi-2020.png';
        }
        if (str_contains($model, 'axia') && $year >= 2023) {
            $configs['axia']['price'] = 130;
            $configs['axia']['image'] = 'axia-2024.png';
        }

        // Find matching config
        foreach ($configs as $key => $config) {
            if (str_contains($model, $key)) {
                return $config;
            }
        }

        // Default Fallback if model is unrecognized
        return [
            'type'    => 'Sedan',
            'price'   => 150,
            'image'   => 'default-car.png',
            'seats'   => 5,
            'luggage' => 2
        ];
    }
}