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
        Schema::create('customer', function (Blueprint $table) {
            
            // Primary Key
            $table->string('matricNum')->primary();

            $table->string('faculty');
            $table->text('collegeAddress')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phoneNum')->nullable();
            $table->string('icNum_passport')->unique()->nullable();
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->integer('postcode')->nullable();
            $table->tinyText('state')->nullable();
            $table->string('eme_name')->nullable();
            $table->string('emephoneNum')->unique()->nullable();
            $table->tinyText('emerelation')->nullable();
            $table->tinyText('bankName')->nullable();
            $table->bigInteger('accountNum')->nullable();
            $table->string('doc_ic_passport')->nullable(); 
            $table->string('doc_license')->nullable();
            $table->string('doc_matric')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
        $table->dropRememberToken();
    });
    }
};
