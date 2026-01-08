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
        Schema::create('inspection', function (Blueprint $table) {
            $table->string('inspectionID')->primary();

            $table->string('frontViewImage')->nullable();
            $table->string('backViewImage')->nullable();
            $table->string('leftViewImage')->nullable();
            $table->string('rightViewImage')->nullable();
            $table->string('interior1Image')->nullable();
            $table->string('interior2Image')->nullable();

            $table->string('type')->nullable();
            $table->double('mileage')->nullable();
            $table->string('fuelImage')->nullable();
            $table->double('fuelBar')->nullable();
            $table->dateTime('dateOut')->nullable();
            $table->dateTime('time')->nullable();
            $table->string('pic')->nullable();
            $table->string('remark')->nullable();
            $table->string('signature')->nullable();
            $table->string('bookingID');
            $table->foreign('bookingID')->references('bookingID')->on('booking')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection');
    }
};
