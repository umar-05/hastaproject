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
            $table->string('plateNumber');
            $table->foreign('plateNumber')->references('plateNumber')->on('fleet');
            $table->string('staffID');
            $table->foreign('staffID')->references('staffID')->on('staff');

            $table->primary(['plateNumber', 'staffID']);
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
