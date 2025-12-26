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
        // Vehicle details page
        return view('vehicles.show', compact('id'));
    }
}