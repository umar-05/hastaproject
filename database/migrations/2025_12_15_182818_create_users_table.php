<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('users', function (Blueprint $table) {
        $table->id(); 
        $table->string('name');
<<<<<<< Updated upstream
        $table->string('email')->unique();  
        $table->string('phone')->nullable();
        $table->string('matric_number')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps(); 
=======
        $table->string('matric_number')->unique(); // No duplicates allowed 
        $table->string('faculty');
        $table->timestamp('email_verified_at')->nullable();
        $table->timestamps(); // Creates 'created_at' and 'updated_at' columns automatically
>>>>>>> Stashed changes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
