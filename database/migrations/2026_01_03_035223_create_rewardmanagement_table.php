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

            $table->string('rewardID');
            $table->foreign('rewardID')->references('rewardID')->on('reward');

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
