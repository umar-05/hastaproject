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
        // 1. Add fields to Customers table
        Schema::table('customers', function (Blueprint $table) {
            // Address details
            if (!Schema::hasColumn('customers', 'college_address')) {
                $table->string('college_address')->nullable();
            }
            
            // Emergency Contact
            if (!Schema::hasColumn('customers', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
                $table->string('emergency_no')->nullable();
                $table->string('emergency_relation')->nullable();
            }

            // Bank Details
            if (!Schema::hasColumn('customers', 'bank_name')) {
                $table->string('bank_name')->nullable();
                $table->string('account_no')->nullable();
            }
        });

        // 2. Add fields to Staff table
        Schema::table('staff', function (Blueprint $table) {
            // Address details (Staff might only have home_address, let's standardize)
            if (!Schema::hasColumn('staff', 'address')) {
                $table->string('address')->nullable(); // Standard address field
            }
            if (!Schema::hasColumn('staff', 'city')) {
                $table->string('city')->nullable();
                $table->string('postcode')->nullable();
                $table->string('state')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'college_address', 
                'emergency_contact_name', 
                'emergency_no', 
                'emergency_relation', 
                'bank_name', 
                'account_no'
            ]);
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['address', 'city', 'postcode', 'state']);
        });
    }
};