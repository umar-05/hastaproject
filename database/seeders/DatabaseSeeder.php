<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Staff first. 
        // This is necessary because your Fleet table has a 'staffID' foreign key.
        $this->call(StaffSeeder::class);

        // 2. Seed the Customer table next.
        // This is necessary because both Fleet and Booking tables reference 'matricNum'.
        // $this->call(CustomerSeeder::class); 

        // 3. Seed the Fleet (vehicles)
        $this->call(FleetSeeder::class);
        


        // 4. Seed Bookings last (as they depend on both Customers and Fleet)
        // $this->call(BookingSeeder::class);

    }
}