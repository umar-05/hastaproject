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
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id('maintenance_id'); // Primary Key

            $table->unsignedBigInteger('fleet_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();

            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();

            $table->timestamps();

            // Optional foreign keys (uncomment if related tables exist)
            // $table->foreign('fleet_id')->references('fleet_id')->on('fleet')->onDelete('set null');
            // $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->onDelete('set null');
            // $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
