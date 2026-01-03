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
            $table->dateTime('redemptionDate');

            $table->foreignId('matricNum')->constrained(table: 'customer', column: 'matricNum');
            $table->foreignId('rewardID')->constrained(table: 'reward', column: 'rewardID');

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
