<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fleet;

class FleetSeeder extends Seeder
{
    public function run()
    {
        $fleets = [
            ['modelName' => 'Perodua Axia', 'plateNumber' => 'WA1234A', 'year' => 2018, 'status' => 'available'],
            ['modelName' => 'Perodua Bezza', 'plateNumber' => 'WA5678B', 'year' => 2018, 'status' => 'available'],
            ['modelName' => 'Perodua Myvi', 'plateNumber' => 'WA9012C', 'year' => 2015, 'status' => 'available'],
            ['modelName' => 'Perodua Myvi', 'plateNumber' => 'WA3456D', 'year' => 2020, 'status' => 'available'],
            ['modelName' => 'Perodua Axia', 'plateNumber' => 'WA7890E', 'year' => 2024, 'status' => 'available'],
            ['modelName' => 'Proton Saga', 'plateNumber' => 'WA2345F', 'year' => 2017, 'status' => 'available'],
            ['modelName' => 'Perodua Alza', 'plateNumber' => 'WA6789G', 'year' => 2019, 'status' => 'available'],
            ['modelName' => 'Perodua Aruz' , 'plateNumber' => 'WA0123H', 'year' => 2020, 'status' => 'available'],
            ['modelName' => 'Toyota Vellfire', 'plateNumber' => 'WA4567I', 'year' => 2020, 'status' => 'available'],
        ];

        foreach ($fleets as $fleet) {
            Fleet::create($fleet);
        }
    }
}