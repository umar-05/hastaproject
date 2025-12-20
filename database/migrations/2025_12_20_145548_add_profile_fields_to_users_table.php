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
    Schema::table('users', function (Blueprint $table) {
        // Contact Info
        $table->string('phoneNum')->nullable(); // nullable means it can be empty initially
        $table->string('icNum')->nullable();
        
        // Address Info
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('postcode')->nullable();
        $table->string('state')->nullable();
        $table->text('collegeAddress')->nullable();
        
        // Emergency Contact
        $table->string('eme_name')->nullable();
        $table->string('emephoneNum')->nullable();
        $table->string('emerelation')->nullable();
        
        // Bank Info
        $table->string('bankName')->nullable();
        $table->string('accountNum')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'phoneNum', 'icNum', 'address', 'city', 'postcode', 'state', 
            'collegeAddress', 'eme_name', 'emephoneNum', 'emerelation', 
            'bankName', 'accountNum'
        ]);
    });
}
};
