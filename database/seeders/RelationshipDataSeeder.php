<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelationshipDataSeeder extends Seeder
{
    public function run(): void
    {
        // Define plate numbers used in your FleetSeeder for reference
        $myvi2020Plate = 'WA3456D'; 
        $axia2018Plate = 'WA1234A';
        $sagaPlate = 'WA2345F';

        // --- 1. Seed MAINTENANCE (using your plate numbers) ---
        DB::table('maintenance')->insert([
            [
                'maintenanceID' => 'MNT-001',
                'plateNumber' => $myvi2020Plate, 
                'description' => 'Brake pad replacement and system check',
                'cost' => 450.00,
                'mDate' => '2025-01-10',
                'mTime' => '13:00',
                'odometerReading' => 18900,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'maintenanceID' => 'MNT-002',
                'plateNumber' => $myvi2020Plate, 
                'description' => 'Engine oil and filter change',
                'cost' => 250.00,
                'mDate' => '2024-07-20',
                'odometerReading' => 12000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
             [
                'maintenanceID' => 'MNT-003',
                'plateNumber' => $axia2018Plate, 
                'description' => 'Tire rotation and alignment',
                'cost' => 120.00,
                'mDate' => '2024-11-05',
                'odometerReading' => 78000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // --- 2. Seed BOOKING (using your plate numbers) ---
        DB::table('booking')->insert([
            [
                'bookingID' => 'BK-001',
                'plateNumber' => $myvi2020Plate,
                'customerName' => 'John Tan',
                'pickupDate' => Carbon::parse('2025-01-15'), // Ongoing booking
                'returnDate' => Carbon::parse('2025-01-20'),
                'totalPrice' => 1000.00,
                'bookingStat' => 'ONGOING',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bookingID' => 'BK-002',
                'plateNumber' => $myvi2020Plate,
                'customerName' => 'Sarah Lee',
                'pickupDate' => Carbon::parse('2024-12-15'),
                'returnDate' => Carbon::parse('2024-12-20'),
                'totalPrice' => 800.00,
                'bookingStat' => 'COMPLETED',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bookingID' => 'BK-003',
                'plateNumber' => $sagaPlate,
                'customerName' => 'Ahmad Rahman',
                'pickupDate' => Carbon::parse('2025-01-05'),
                'returnDate' => Carbon::parse('2025-01-12'),
                'totalPrice' => 1200.00,
                'bookingStat' => 'ONGOING',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}