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
        Schema::table('booking', function (Blueprint $table) {
            // Add fleet/car reference
            $table->unsignedBigInteger('fleet_id')->nullable()->after('customer_id');
            
            // Add reward reference
            $table->unsignedBigInteger('reward_id')->nullable()->after('fleet_id');
            
            // Add voucher code
            $table->string('voucher_code')->nullable()->after('reward_id');
            
            // Add time fields to dates
            $table->time('pickup_time')->nullable()->after('pickup_date');
            $table->time('return_time')->nullable()->after('return_date');
            
            // Add pricing breakdown
            $table->decimal('base_price', 10, 2)->nullable()->after('total_price');
            $table->decimal('discount', 10, 2)->default(0)->after('base_price');
            
            // Add payment status
            $table->string('payment_status')->default('pending')->after('booking_stat');
            
            // Add deposit refund tracking
            $table->timestamp('deposit_refunded_at')->nullable()->after('payment_status');
            
            // Add notes field
            $table->text('notes')->nullable()->after('deposit_refunded_at');
            
            // Add foreign keys (optional - uncomment if needed)
            // $table->foreign('fleet_id')->references('id')->on('fleet')->onDelete('set null');
            // $table->foreign('reward_id')->references('id')->on('reward')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn([
                'fleet_id',
                'reward_id',
                'voucher_code',
                'pickup_time',
                'return_time',
                'base_price',
                'discount',
                'payment_status',
                'deposit_refunded_at',
                'notes'
            ]);
        });
    }
};