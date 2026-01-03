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
        Schema::create('booking', function (Blueprint $table) {
            $table->string('bookingID')->primary();
            
            $table->dateTime('pickupDate')->nullable();
            $table->dateTime('returnDate')->nullable();
            $table->string('pickupLoc')->nullable();
            $table->string('returnLoc')->nullable();
            $table->double('deposit')->nullable();
            $table->double('totalPrice')->nullable();
            $table->string('bookingStat')->nullable();
            $table->string('feedback')->nullable();
            
            
            // FK
            $table->string('plateNumber')->nullable();
            $table->foreign('plateNumber')->references('plateNumber')->on('fleet');

            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customer');
            
            $table->string('rewardID')->nullable();
            $table->foreign('rewardID')->references('rewardID')->on('reward');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
