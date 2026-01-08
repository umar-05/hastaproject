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
        Schema::create('reward', function (Blueprint $table) {
            $table->string('rewardID')->primary();

            $table->tinyInteger('rewardPoints');
            $table->string('voucherCode');
            $table->mediumText('rewardType');
            $table->integer('rewardAmount');
            $table->tinyInteger('totalClaimable');
            $table->tinyInteger('claimedCount')->default(0);
            $table->date('expiryDate');
            $table->mediumText('rewardStatus');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward');
    }
};
