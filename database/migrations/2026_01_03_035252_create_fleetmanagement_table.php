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
        Schema::create('fleetmanagement', function (Blueprint $table) {
            $table->foreignId('vehicleID')->constrained(table: 'fleet', column: 'vehicleID');
            $table->foreignId('staffID')->constrained(table: 'staff', column: 'staffID');

            $table->primary(['vehicleID', 'staffID']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleetmanagement');
    }
};
