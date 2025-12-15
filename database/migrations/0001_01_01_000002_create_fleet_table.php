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
            $table->id('fleet_id');     // Primary Key

            $table->string('model_name');
            $table->string('plate_number')->unique();
            $table->year('year')->nullable();
            $table->string('ownership')->nullable();

            // Roadtax details
            $table->string('roadtax')->nullable();
            $table->string('roadtax_stat')->nullable();
            $table->date('roadtax_start_date')->nullable();
            $table->date('roadtax_end_date')->nullable();

            // Insurance details
            $table->string('insurance')->nullable();
            $table->string('insurance_stat')->nullable();
            $table->date('insurance_start_date')->nullable();
            $table->date('insurance_end_date')->nullable();

            $table->string('status')->nullable();  // e.g. active, inactive, maintenance
            $table->text('note')->nullable();

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
