<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update staff user
        Staff::updateOrCreate(
            [
                'name' => 'Staff User',
                'email' => 'staff@hasta.com',
                'password' => Hash::make('password'),
                'position' => 'staff',
                'staffID' => 'STAFF001',
                //'email_verified_at' => now(),
            ]
        );
    }
}
