<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure role column exists before seeding
        if (Schema::hasColumn('users', 'role')) {
            // Create staff user if it doesn't exist
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally remove staff user on rollback
        User::where('email', 'staff@hasta.com')->delete();
    }
};
