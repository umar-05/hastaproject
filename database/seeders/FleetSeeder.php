<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fleet;

class FleetSeeder extends Seeder
{
    public function run()
    {
        $fleets = [
            ['modelName' => 'Perodua Axia 2018', 'plateNumber' => 'WA1234A', 'year' => 2018, 'status' => 'available'],
            ['modelName' => 'Perodua Bezza 2018', 'plateNumber' => 'WA5678B', 'year' => 2018, 'status' => 'available'],
            ['modelName' => 'Perodua Myvi 2015', 'plateNumber' => 'WA9012C', 'year' => 2015, 'status' => 'available'],
            ['modelName' => 'Perodua Myvi 2020', 'plateNumber' => 'WA3456D', 'year' => 2020, 'status' => 'available'],
            ['modelName' => 'Perodua Axia 2024', 'plateNumber' => 'WA7890E', 'year' => 2024, 'status' => 'available'],
            ['modelName' => 'Proton Saga 2017', 'plateNumber' => 'WA2345F', 'year' => 2017, 'status' => 'available'],
            ['modelName' => 'Perodua Alza 2019', 'plateNumber' => 'WA6789G', 'year' => 2019, 'status' => 'available'],
            ['modelName' => 'Perodua Aruz 2020', 'plateNumber' => 'WA0123H', 'year' => 2020, 'status' => 'available'],
            ['modelName' => 'Toyota Vellfire 2020', 'plateNumber' => 'WA4567I', 'year' => 2020, 'status' => 'available'],
        ];

        foreach ($fleets as $fleet) {
            Fleet::create($fleet);
        }
    }
}