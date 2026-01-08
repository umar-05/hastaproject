<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fleet;
use Carbon\Carbon;

class FleetSeeder extends Seeder
{
    public function run()
    {
        // Common dates for seeding
        $today = Carbon::now();
        $nextYear = Carbon::now()->addYear();

        $fleets = [
            // 1. Sedan (Proton Saga)
            [
                'plateNumber' => 'VHJ8821',
                'modelName' => 'Proton Saga Premium',
                'year' => 2023,
                'color' => 'Jet Grey',
                'status' => 'available',
                'photo1' => 'saga-2017.png', // <--- Added Image
                'roadtaxStat' => 'Active',
                'roadtaxActiveDate' => $today->toDateString(),
                'roadtaxExpiryDate' => $nextYear->toDateString(),
                'insuranceStat' => 'Active',
                'insuranceActiveDate' => $today->toDateString(),
                'insuranceExpiryDate' => $nextYear->toDateString(),
                'ownerIC' => '050716040127'
            ],
            // 2. Hatchback (Perodua Myvi)
            [
                'plateNumber' => 'JQV4512',
                'modelName' => 'Perodua Myvi 1.5 AV',
                'year' => 2022,
                'color' => 'Cranberry Red',
                'status' => 'available',
                'photo1' => 'myvi-2020.png', // <--- Added Image
                'roadtaxStat' => 'Active',
                'roadtaxActiveDate' => $today->toDateString(),
                'roadtaxExpiryDate' => $nextYear->toDateString(),
                'insuranceStat' => 'Active',
                'insuranceActiveDate' => $today->toDateString(),
                'insuranceExpiryDate' => $nextYear->toDateString(),
                'ownerIC' => '930204014449'
            ],
            // 3. MPV (Perodua Alza)
            [
                'plateNumber' => 'W3344X',
                'modelName' => 'Perodua Alza',
                'year' => 2023,
                'color' => 'Vintage Brown',
                'status' => 'available',
                'photo1' => 'alza-2019.png', // <--- Added Image
                'roadtaxStat' => 'Active',
                'roadtaxActiveDate' => $today->toDateString(),
                'roadtaxExpiryDate' => $nextYear->toDateString(),
                'insuranceStat' => 'Active',
                'insuranceActiveDate' => $today->toDateString(),
                'insuranceExpiryDate' => $nextYear->toDateString(),
                'ownerIC' => '000101030000'
            ],
            // 4. SUV (Proton X50)
            [
                'plateNumber' => 'BQC1029',
                'modelName' => 'Proton X50 Flagship',
                'year' => 2024,
                'color' => 'Passion Red',
                'status' => 'available',
                'photo1' => 'x50-2024.png', // <--- Added Image
                'roadtaxStat' => 'Active',
                'roadtaxActiveDate' => $today->toDateString(),
                'roadtaxExpiryDate' => $nextYear->toDateString(),
                'insuranceStat' => 'Active',
                'insuranceActiveDate' => $today->toDateString(),
                'insuranceExpiryDate' => $nextYear->toDateString(),
                'ownerIC' => '991231149999'
            ],
            // 5. Motorcycle (Yamaha Y15ZR)
            [
                'plateNumber' => 'KDD9922',
                'modelName' => 'Yamaha Y15ZR V2',
                'year' => 2023,
                'color' => 'Cyan',
                'status' => 'available',
                'photo1' => 'y15zr-2023.png', // <--- Added Image
                'roadtaxStat' => 'Active',
                'roadtaxActiveDate' => $today->toDateString(),
                'roadtaxExpiryDate' => $nextYear->toDateString(),
                'insuranceStat' => 'Active',
                'insuranceActiveDate' => $today->toDateString(),
                'insuranceExpiryDate' => $nextYear->toDateString(),
                'ownerIC' => '010923106767'
            ],
        ];

        foreach ($fleets as $fleet) {
            // Use updateOrCreate to avoid duplicate entry errors if seeding multiple times
            Fleet::updateOrCreate(
                ['plateNumber' => $fleet['plateNumber']], // Check by plate number
                $fleet
            );
        }
    }
}