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
            $table->id('bookingID');
            
            $table->dateTime('pickupDate');
            $table->dateTime('returnDate');
            $table->string('pickupLoc');
            $table->string('returnLoc');
            $table->double('deposit');
            $table->double('totalPrice');
            $table->string('bookingStat');
            $table->string('feedback');
            
            
            // FK
            $table->foreignId('matricNum')->constrained(table: 'customer', column: 'matricNum');
            $table->foreignId('rewardID')->constrained(table: 'reward', column: 'rewardID');
            $table->foreignId('vehicleID')->constrained(table: 'fleet', column: 'vehicleID');



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
