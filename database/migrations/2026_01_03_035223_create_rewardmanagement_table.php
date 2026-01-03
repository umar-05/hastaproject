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
            $table->string('staffID');
            $table->foreign('staffID')->references('staffID')->on('staff');
            $table->foreignId('rewardID')->constrained(table: 'reward', column: 'rewardID');

            $table->primary(['staffID', 'rewardID']);
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
