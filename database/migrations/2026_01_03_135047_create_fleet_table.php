<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fleet', function (Blueprint $table) {
            $table->string('plateNumber')->primary(); 
            $table->string('modelName');
            $table->integer('year');
            $table->string('photos')->nullable();
            
            $table->string('roadtaxStat')->nullable();
            $table->date('taxActivedate')->nullable();
            $table->date('taxExpirydate')->nullable();
            $table->string('insuranceStat')->nullable();
            $table->date('insuranceActivedate')->nullable();
            $table->date('insuranceExpirydate')->nullable();
            
            $table->string('status')->default('available');
            $table->text('note')->nullable();
        
            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customer');
            
            $table->string('staffID')->nullable();
            $table->foreign('staffID')->references('staffID')->on('staff');

            $table->string('inspectionID')->nullable();
            $table->foreign('inspectionID')->references('inspectionID')->on('inspection');

            $table->string('maintenanceID')->nullable();
            $table->foreign('maintenanceID')->references('maintenanceID')->on('maintenance');

            $table->string('ownerIC')->nullable();
            $table->foreign('ownerIC')->references('ownerIC')->on('owner');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet');
    }
};