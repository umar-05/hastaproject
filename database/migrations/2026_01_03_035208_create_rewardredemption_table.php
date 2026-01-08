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

            $table->dateTime('redemptionDate')->nullable();

            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customer');

            $table->string('rewardID')->nullable();
            $table->foreign('rewardID')->references('rewardID')->on('reward');
            
            $table->string('status')->nullable();

            $table->primary(['matricNum', 'rewardID']);

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
