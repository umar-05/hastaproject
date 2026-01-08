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
        Schema::create('owner', function (Blueprint $table) {
            $table->string('ownerIC')->primary();

            $table->string('ownerName')->nullable();
            $table->string('ownerEmail')->unique()->nullable();
            $table->string('ownerPhoneNum')->unique()->nullable();
            $table->tinyText('roadtaxStat')->nullable();
            $table->date('roadtaxActiveDate')->nullable();
            $table->date('roadtaxExpiryDate')->nullable();
            $table->tinyText('insuranceStat')->nullable();
            $table->date('insuranceActiveDate')->nullable();
            $table->date('insuranceExpiryDate')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner');
    }
};
