<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fleet;

class FleetSeeder extends Seeder
{
    public function run()
    {
        $fleets = [
            ['model_name' => 'Perodua Axia 2018', 'plate_number' => 'WA1234A', 'year' => 2018, 'status' => 'available'],
            ['model_name' => 'Perodua Bezza 2018', 'plate_number' => 'WA5678B', 'year' => 2018, 'status' => 'available'],
            ['model_name' => 'Perodua Myvi 2015', 'plate_number' => 'WA9012C', 'year' => 2015, 'status' => 'available'],
            ['model_name' => 'Perodua Myvi 2020', 'plate_number' => 'WA3456D', 'year' => 2020, 'status' => 'available'],
            ['model_name' => 'Perodua Axia 2024', 'plate_number' => 'WA7890E', 'year' => 2024, 'status' => 'available'],
            ['model_name' => 'Proton Saga 2017', 'plate_number' => 'WA2345F', 'year' => 2017, 'status' => 'available'],
            ['model_name' => 'Perodua Alza 2019', 'plate_number' => 'WA6789G', 'year' => 2019, 'status' => 'available'],
            ['model_name' => 'Perodua Aruz 2020', 'plate_number' => 'WA0123H', 'year' => 2020, 'status' => 'available'],
            ['model_name' => 'Toyota Vellfire 2020', 'plate_number' => 'WA4567I', 'year' => 2020, 'status' => 'available'],
        ];

        foreach ($fleets as $fleet) {
            Fleet::create($fleet);
        }
    }
}