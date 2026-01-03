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
        Schema::create('bookingmanagement', function (Blueprint $table) {

            $table->string('bookingID');
            $table->foreign('bookingID')->references('bookingID')->on('booking');

            $table->string('staffID');
            $table->foreign('staffID')->references('staffID')->on('staff');

            $table->primary(['bookingID', 'staffID']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookingmanagement');
    }
};
