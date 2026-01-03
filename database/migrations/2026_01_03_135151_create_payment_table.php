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
        Schema::create('payment', function (Blueprint $table) {
            $table->string('paymentID')->primary();
            
            $table->string('paymentStatus')->nullable();
            $table->string('method', 50)->nullable();
            $table->dateTime('paymentDate')->nullable();
            $table->decimal('discountedPrice', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('grandTotal', 10, 2)->nullable();
            
            $table->string('bookingID')->nullable();
            $table->foreign('bookingID')->references('bookingID')->on('booking');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
