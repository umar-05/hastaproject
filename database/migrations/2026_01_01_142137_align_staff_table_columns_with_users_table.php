<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // 1. Rename existing columns to match User table naming convention
            // Note: If you are using SQLite/older MySQL, ensure you have doctrine/dbal installed.
            // If strict renaming fails, you may need to drop and re-add, but usually this works in modern Laravel.
            
            // Check if column exists before renaming to prevent errors
            if (Schema::hasColumn('staff', 'phone_no')) {
                $table->renameColumn('phone_no', 'phoneNum');
            }
            if (Schema::hasColumn('staff', 'ic_no')) {
                $table->renameColumn('ic_no', 'icNum');
            }

            // 2. Add missing columns found in Users table
            if (!Schema::hasColumn('staff', 'matric_number')) {
                $table->string('matric_number')->unique()->nullable()->after('email');
            }
            if (!Schema::hasColumn('staff', 'faculty')) {
                $table->string('faculty')->nullable()->after('matric_number');
            }
            
            // Address Extras
            if (!Schema::hasColumn('staff', 'collegeAddress')) {
                $table->text('collegeAddress')->nullable()->after('state');
            }

            // Emergency Contact (Matching User table names)
            if (!Schema::hasColumn('staff', 'eme_name')) {
                $table->string('eme_name')->nullable();
                $table->string('emephoneNum')->nullable();
                $table->string('emerelation')->nullable();
            }

            // Bank Info (Matching User table names)
            if (!Schema::hasColumn('staff', 'bankName')) {
                $table->string('bankName')->nullable();
                $table->string('accountNum')->nullable();
            }

            // 3. Drop columns that are now redundant or not in Users table schema
            // 'home_address' is redundant if we are using 'address' to match Users
            if (Schema::hasColumn('staff', 'home_address')) {
                $table->dropColumn('home_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Revert renames
            $table->renameColumn('phoneNum', 'phone_no');
            $table->renameColumn('icNum', 'ic_no');

            // Drop added columns
            $table->dropColumn([
                'matric_number',
                'faculty',
                'collegeAddress',
                'eme_name',
                'emephoneNum',
                'emerelation',
                'bankName',
                'accountNum'
            ]);

            // Restore dropped column
            $table->text('home_address')->nullable();
        });
    }
};