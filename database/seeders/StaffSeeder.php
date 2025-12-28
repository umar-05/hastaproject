<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::updateOrCreate(
            ['email' => 'staff@hasta.com'],
            [
                'name' => 'Staff User',
                'email' => 'staff@hasta.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'matric_number' => 'STAFF001',
                'faculty' => 'Administration',
                'email_verified_at' => now(),
            ]
        );
    }
}
