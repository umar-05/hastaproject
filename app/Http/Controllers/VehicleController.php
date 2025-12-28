<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = [
            [
                'id' => 1,
                'name' => 'Perodua Axia 2018',
                'type' => 'Hatchback',
                'price' => 120,
                'image' => 'axia-2018.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 2,
                'name' => 'Perodua Bezza 2018',
                'type' => 'Sedan',
                'price' => 140,
                'image' => 'bezza-2018.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 3,
                'name' => 'Perodua Myvi 2015',
                'type' => 'Hatchback',
                'price' => 120,
                'image' => 'myvi-2015.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 4,
                'name' => 'Perodua Myvi 2020',
                'type' => 'Hatchback',
                'price' => 150,
                'image' => 'myvi-2020.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 5,
                'name' => 'Perodua Axia 2024',
                'type' => 'Hatchback',
                'price' => 130,
                'image' => 'axia-2024.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 6,
                'name' => 'Proton Saga 2017',
                'type' => 'Sedan',
                'price' => 120,
                'image' => 'saga-2017.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 7,
                'name' => 'Perodua Alza 2019',
                'type' => 'MPV',
                'price' => 200,
                'image' => 'alza-2019.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 8,
                'name' => 'Perodua Aruz 2020',
                'type' => 'SUV',
                'price' => 180,
                'image' => 'aruz-2020.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ],
            [
                'id' => 9,
                'name' => 'Toyota Vellfire 2020',
                'type' => 'MPV',
                'price' => 500,
                'image' => 'vellfire-2020.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true
            ]
        ];

        return view('vehicles.index', compact('vehicles'));
    }

    public function show($id)
    {
        $vehicles = [
            [
                'id' => 1,
                'name' => 'Perodua Axia 2018',
                'type' => 'Hatchback',
                'price' => 120,
                'image' => 'axia-2018.png',
                'transmission' => 'Automat',
                'fuel' => 'RON 95',
                'ac' => true,
                'description' => 'Perfect for city driving, the Perodua Axia 2018 offers excellent fuel efficiency and compact design. Ideal for navigating through busy streets and tight parking spaces.',
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
                'description' => 'A comfortable sedan with spacious interior and smooth ride. The Perodua Bezza 2018 is perfect for longer journeys and family trips.',
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
                'description' => 'The popular Perodua Myvi 2015 combines style and practicality. Known for its reliability and excellent resale value.',
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
                'description' => 'The newer generation Myvi 2020 features updated styling and improved features. A perfect blend of comfort and performance.',
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
                'description' => 'The latest Perodua Axia 2024 with modern features and enhanced safety. Perfect for those who want the newest technology.',
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
                'description' => 'A reliable and affordable sedan, the Proton Saga 2017 offers great value for money with decent features and comfortable ride.',
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
                'description' => 'Spacious MPV perfect for family trips and group travels. The Perodua Alza 2019 offers comfortable seating for 7 passengers with ample luggage space.',
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
                'description' => 'A robust SUV perfect for adventurous journeys. The Perodua Aruz 2020 offers excellent ground clearance and spacious interior.',
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
                'description' => 'Luxury MPV with premium features and exceptional comfort. The Toyota Vellfire 2020 is perfect for VIP transport and executive travel.',
                'seats' => 7,
                'luggage' => 6
            ]
        ];

        $vehicle = collect($vehicles)->firstWhere('id', (int) $id);
        
        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }

        return view('vehicles.show', compact('vehicle'));
    }
}