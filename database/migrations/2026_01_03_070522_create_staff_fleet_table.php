<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fleet', function (Blueprint $table) {
            $table->string('road_tax_amount')->nullable();     // e.g., "120.00"
            $table->date('road_tax_expiry')->nullable();
            $table->string('insurance_amount')->nullable();    // e.g., "1800.00"
            $table->date('insurance_expiry')->nullable();
            $table->text('staff_notes')->nullable();
            $table->boolean('is_company_owned')->default(true);
        });
    }

    public function down()
    {
        Schema::table('fleet', function (Blueprint $table) {
            $table->dropColumn([
                'road_tax_amount',
                'road_tax_expiry',
                'insurance_amount',
                'insurance_expiry',
                'staff_notes',
                'is_company_owned'
            ]);
        });
    }
};