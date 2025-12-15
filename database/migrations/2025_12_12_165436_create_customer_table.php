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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id');               // Primary Key
            $table->string('name');
            $table->string('email')->unique();
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_no')->nullable();
            $table->string('emergency_relation')->nullable();

            $table->string('phone_no')->nullable();
            $table->string('ic_no')->nullable();
            $table->string('matric_no')->nullable();
            $table->string('faculty')->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('state')->nullable();
            $table->text('college_address')->nullable();

            // Bank Info
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();

            // Login
            $table->string('password');

            // Reward points
            $table->integer('reward_points')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
