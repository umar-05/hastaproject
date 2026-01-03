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
        Schema::create('rewardmanagement', function (Blueprint $table) {
            $table->id('staffID');
            $table->id('rewardID');

            $table->foreignId('staffID')->constrained(table: 'staff', column: 'staffID');
            $table->foreignId('rewardID')->constrained(table: 'reward', column: 'rewardID');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewardmanagement');
    }
};
