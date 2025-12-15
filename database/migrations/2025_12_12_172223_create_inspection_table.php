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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id('inspection_id'); // Primary Key

            // Outer photos (1..6)
            $table->string('outer_photo_1')->nullable();
            $table->string('outer_photo_2')->nullable();
            $table->string('outer_photo_3')->nullable();
            $table->string('outer_photo_4')->nullable();
            $table->string('outer_photo_5')->nullable();
            $table->string('outer_photo_6')->nullable();

            $table->integer('mileage')->nullable();

            // Fuel info
            $table->string('fuel')->nullable();
            $table->string('fuel_image')->nullable();
            $table->integer('fuel_bar')->nullable(); // e.g., 0–100

            $table->date('date_out')->nullable();
            $table->time('time_out')->nullable();

            $table->string('pic')->nullable();    // person-in-charge / image path
            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
