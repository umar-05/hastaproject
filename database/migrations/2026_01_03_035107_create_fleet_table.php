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
        Schema::create('fleet', function (Blueprint $table) {
            $table->id('plateNumber');
            $table->string('modelName');
            $table->integer('year');
            $table->string('photos');
            $table->string('ownerName');
            $table->integer('ownerIc');
            $table->longint('ownerPhone');
            $table->string('ownerEmail');
            $table->string('roadtaxStat');
            $table->date('taxActivedate');
            $table->date('taxExpirydate');
            $table->string('insuranceStat');
            $table->date('insuranceActivedate');
            $table->date('insuranceExpirydate');
            $table->string('status');
            $table->text('note');
        
            $table->foreignId('matricNum')->constrained(table: 'customer', column: 'matricNum');
            $table->foreignId('staffID')->constrained(table: 'staff', column: 'staffID');

               $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet');
    }
};
