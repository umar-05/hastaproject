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
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staffID');

            $table->string('position');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->longint('phoneNum')->nullable();
            $table->string('icNum')->unique();
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->int('postcode')->nullable();
            $table->tinyText('state')->nullable();
            $table->string('eme_name')->nullable();
            $table->int('emephoneNum')->unique();
            $table->tinyText('emerelation')->nullable();
            $table->tinyText('bankName')->nullable();
            $table->longint('accountNum')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
