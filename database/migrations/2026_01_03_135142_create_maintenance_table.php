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
            $table->string('maintenanceID')->primary();

            $table->text('description')->nullable();
            $table->date('mDate')->nullable();
            $table->time('mTime')->nullable();
            $table->decimal('cost', 10, 2)->nullable();

            $table->string('plateNumber')->nullable(); 
            $table->foreign('plateNumber')->references('plateNumber')->on('fleet');

            $table->timestamps();
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
