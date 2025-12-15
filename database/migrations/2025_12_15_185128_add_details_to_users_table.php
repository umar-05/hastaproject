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
    Schema::table('users', function (Blueprint $table) {
        // Adding username and phone number
        $table->string('username')->unique()->after('id'); 
        $table->string('phone')->nullable()->after('username');
        
        // Note: The default migration usually has 'name'. 
        // If you want to replace 'name' with 'username', you can drop 'name'.
        // For now, we will just add these new ones.
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['username', 'phone']);
    });
}
};
