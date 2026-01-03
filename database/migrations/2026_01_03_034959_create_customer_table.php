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
            $table->id('matricNum');

            $table->string('faculty');
            $table->text('collegeAddress');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->longint('phoneNum');
            $table->string('icNum');
            $table->text('address');
            $table->int('postcode');
            $table->tinyText('state');
            $table->string('eme_name');
            $table->int('emephoneNum');
            $table->tinyText('emerelation');
            $table->tinyText('bankName');
            $table->longint('accountNum');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
