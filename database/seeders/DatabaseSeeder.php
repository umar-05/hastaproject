<?php

namespace Database\Seeders;

use App\Models\User;
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
        // 1. Seed the car fleet first (so bookings have vehicles to link to)
        $this->call(FleetSeeder::class);

        // 2. Seed staff users
        $this->call(StaffSeeder::class);

        // 3. Create a default Test User for easy login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            // If you want a fixed password, add: 'password' => bcrypt('password123'),
        ]);

        // Optional: Create 10 random customers
        // User::factory(10)->create();
    }
}