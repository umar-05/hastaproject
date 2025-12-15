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
        Schema::create('booking', function (Blueprint $table) {
            $table->id('booking_id');  // Primary Key

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->date('pickup_date')->nullable();
            $table->date('return_date')->nullable();

            $table->string('pickup_loc')->nullable();
            $table->string('return_loc')->nullable();

            $table->decimal('deposit', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            $table->string('booking_stat')->nullable(); // e.g. pending, confirmed, cancelled

            $table->timestamps();

            // OPTIONAL foreign key (uncomment if customers table exists)
            // $table->foreign('customer_id')
            //       ->references('customer_id')->on('customers')
            //       ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
