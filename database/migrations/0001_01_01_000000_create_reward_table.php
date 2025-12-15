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
        Schema::create('reward', function (Blueprint $table) {
            $table->id('reward_id');   // Primary Key

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();

            $table->integer('reward_points')->default(0);

            $table->string('voucher_code')->nullable();  // Fixed typo: vouchercode → voucher_code
            $table->string('reward_item')->nullable();

            $table->timestamps();

            // OPTIONAL FOREIGN KEYS (uncomment if tables exist)
            // $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('set null');
            // $table->foreign('booking_id')->references('booking_id')->on('booking')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward');
    }
};
