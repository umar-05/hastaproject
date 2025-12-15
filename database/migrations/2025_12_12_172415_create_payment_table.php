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
            $table->id('payment_id'); // Primary Key

            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('reward_id')->nullable();

            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('method')->nullable();         // e.g., cash, credit, online
            $table->string('payment_status')->nullable(); // e.g., pending, completed
            $table->decimal('grand_total', 10, 2)->nullable();

            $table->timestamps();

            // Optional foreign keys (uncomment if related tables exist)
            // $table->foreign('booking_id')->references('booking_id')->on('booking')->onDelete('set null');
            // $table->foreign('reward_id')->references('reward_id')->on('reward')->onDelete('set null');
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
