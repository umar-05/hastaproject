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
            $table->bigInteger('ownerPhoneNum')->unique()->nullable();
            $table->tinyText('roadtaxStat')->nullable();
            $table->date('taxActivedate')->nullable();
            $table->date('taxExpirydate')->nullable();
            $table->tinyText('insuranceStat')->nullable();
            $table->date('insuranceActiveDate')->nullable();
            $table->date('insuranceExpirydate')->nullable();
            
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
