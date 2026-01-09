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
        Schema::create('rewardredemption', function (Blueprint $table) {
            // 1. We need an auto-incrementing ID to allow history (e.g. 1 Active, 5 Used)
            $table->id();

            $table->dateTime('redemptionDate')->nullable();

            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customer')->onDelete('cascade');

            $table->string('rewardID')->nullable();
            $table->foreign('rewardID')->references('rewardID')->on('reward')->onDelete('cascade');
            
            $table->string('bookingID')->nullable();
            $table->foreign('bookingID')->references('bookingID')->on('booking')->onDelete('cascade');

            // Default status is Active when created
            $table->string('status')->default('Active'); 

            // REMOVED the old composite primary key line to allow multiple claims over time
            // $table->primary(['matricNum', 'rewardID']); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewardredemption');
    }
};