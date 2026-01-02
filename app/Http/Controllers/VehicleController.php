<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fleet;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = \App\Models\Fleet::all(); // Fetches all cars from the database
        return view('vehicles.index', compact('vehicles'));
    }

    public function bookNow()
    {
        // Fetch all vehicles from the database
        $vehicles = Fleet::where('status', 'available')->get()->map(function($fleet) {
            $vehicleInfo = $this->getVehicleInfo($fleet->model_name, $fleet->year);
            
            return [
                'id' => $fleet->fleet_id,
                'name' => $fleet->model_name . ($fleet->year ? ' ' . $fleet->year : ''),
                'type' => $vehicleInfo['type'],
                'price' => $vehicleInfo['price'],
                'image' => $vehicleInfo['image'],
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ];
        })->toArray();

        // If database is empty, use fallback hardcoded data
        if (empty($vehicles)) {
            $vehicles = $this->getHardcodedVehicles();
        }

        return view('customer.book-now', compact('vehicles'));
    }

    /**
     * Get vehicle type, price, and image based on model name
     */
    private function getVehicleInfo($modelName, $year = null)
    {
        $modelName = strtolower($modelName);
        $year = $year ?? '';
        
        // Determine vehicle type
        $type = 'Sedan'; // default
        if (strpos($modelName, 'axia') !== false || strpos($modelName, 'myvi') !== false) {
            $type = 'Hatchback';
        } elseif (strpos($modelName, 'bezza') !== false || strpos($modelName, 'saga') !== false) {
            $type = 'Sedan';
        } elseif (strpos($modelName, 'alza') !== false || strpos($modelName, 'vellfire') !== false) {
            $type = 'MPV';
        } elseif (strpos($modelName, 'aruz') !== false) {
            $type = 'SUV';
        }
        
        // Determine image filename
        $image = 'default-car.png';
        if (strpos($modelName, 'axia') !== false) {
            $image = $year == 2024 ? 'axia-2024.png' : 'axia-2018.png';
        } elseif (strpos($modelName, 'bezza') !== false) {
            $image = 'bezza-2018.png';
        } elseif (strpos($modelName, 'myvi') !== false) {
            $image = $year >= 2020 ? 'myvi-2020.png' : 'myvi-2015.png';
        } elseif (strpos($modelName, 'saga') !== false) {
            $image = 'saga-2017.png';
        } elseif (strpos($modelName, 'alza') !== false) {
            $image = 'alza-2019.png';
        } elseif (strpos($modelName, 'aruz') !== false) {
            $image = 'aruz-2020.png';
        } elseif (strpos($modelName, 'vellfire') !== false) {
            $image = 'vellfire-2020.png';
        }
        
        // Determine price
        $price = 120; // default
        if (strpos($modelName, 'bezza') !== false) {
            $price = 140;
        } elseif (strpos($modelName, 'myvi') !== false && $year >= 2020) {
            $price = 150;
        } elseif (strpos($modelName, 'axia') !== false && $year == 2024) {
            $price = 130;
        } elseif (strpos($modelName, 'alza') !== false) {
            $price = 200;
        } elseif (strpos($modelName, 'aruz') !== false) {
            $price = 180;
        } elseif (strpos($modelName, 'vellfire') !== false) {
            $price = 500;
        }
        
        return [
            'type' => $type,
            'price' => $price,
            'image' => $image
        ];
    }

    public function show($id)
    {
        // Try to find the fleet in database
        $fleet = Fleet::where('fleet_id', $id)->first();
        
        if ($fleet) {
            // If found in database, convert to array format for the view
            $vehicleInfo = $this->getVehicleInfo($fleet->model_name, $fleet->year);
            $vehicle = [
                'id' => $fleet->fleet_id,
                'name' => $fleet->model_name . ($fleet->year ? ' ' . $fleet->year : ''),
                'type' => $vehicleInfo['type'],
                'price' => $vehicleInfo['price'],
                'image' => $vehicleInfo['image'],
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'A reliable vehicle for your travel needs.',
                'seats' => 5,
                'luggage' => 2
            ];
        } else {
            // If not found, check hardcoded data
            $hardcodedVehicles = $this->getHardcodedVehicles();
            $vehicle = collect($hardcodedVehicles)->firstWhere('id', $id);
            
            if (!$vehicle) {
                abort(404, 'Vehicle not found');
            }
        }
        
        return view('vehicles.show', compact('vehicle'));
    }

    // Fallback hardcoded data
    private function getHardcodedVehicles()
    {
        return [
            [
                'id' => 1,
                'name' => 'Perodua Axia 2018',
                'type' => 'Hatchback',
                'price' => 120,
                'image' => 'axia-2018.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'Perfect for city driving, the Perodua Axia 2018 offers excellent fuel efficiency and compact design.',
                'seats' => 5,
                'luggage' => 2
            ],
            [
                'id' => 2,
                'name' => 'Perodua Bezza 2018',
                'type' => 'Sedan',
                'price' => 140,
                'image' => 'bezza-2018.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'A comfortable sedan with spacious interior and smooth ride.',
                'seats' => 5,
                'luggage' => 3
            ],
            [
                'id' => 3,
                'name' => 'Perodua Myvi 2015',
                'type' => 'Hatchback',
                'price' => 120,
                'image' => 'myvi-2015.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'The popular Perodua Myvi 2015 combines style and practicality.',
                'seats' => 5,
                'luggage' => 2
            ],
            [
                'id' => 4,
                'name' => 'Perodua Myvi 2020',
                'type' => 'Hatchback',
                'price' => 150,
                'image' => 'myvi-2020.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'The newer generation Myvi 2020 features updated styling and improved features.',
                'seats' => 5,
                'luggage' => 2
            ],
            [
                'id' => 5,
                'name' => 'Perodua Axia 2024',
                'type' => 'Hatchback',
                'price' => 130,
                'image' => 'axia-2024.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'The latest Perodua Axia 2024 with modern features and enhanced safety.',
                'seats' => 5,
                'luggage' => 2
            ],
            [
                'id' => 6,
                'name' => 'Proton Saga 2017',
                'type' => 'Sedan',
                'price' => 120,
                'image' => 'saga-2017.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'A reliable and affordable sedan, the Proton Saga 2017 offers great value for money.',
                'seats' => 5,
                'luggage' => 3
            ],
            [
                'id' => 7,
                'name' => 'Perodua Alza 2019',
                'type' => 'MPV',
                'price' => 200,
                'image' => 'alza-2019.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'Spacious MPV perfect for family trips and group travels.',
                'seats' => 7,
                'luggage' => 4
            ],
            [
                'id' => 8,
                'name' => 'Perodua Aruz 2020',
                'type' => 'SUV',
                'price' => 180,
                'image' => 'aruz-2020.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'A robust SUV perfect for adventurous journeys.',
                'seats' => 7,
                'luggage' => 5
            ],
            [
                'id' => 9,
                'name' => 'Toyota Vellfire 2020',
                'type' => 'MPV',
                'price' => 500,
                'image' => 'vellfire-2020.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'Luxury MPV with premium features and exceptional comfort.',
                'seats' => 7,
                'luggage' => 6
            ]
        ];
    }
}