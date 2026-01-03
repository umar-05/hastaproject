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
            $table->foreignID('bookingID')->constrained(table: 'booking', column: 'bookingID');
            $table->foreignID('staffID')->constrained(table: 'staff', column: 'staffID');

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
