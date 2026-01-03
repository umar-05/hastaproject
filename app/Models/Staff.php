<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Changed to support login
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';
    
    // Match the exact casing from your phpMyAdmin screenshot
    protected $primaryKey = 'staffID'; 

    // Tell Laravel the PK is a string (STAFF001) and not a number
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'staffID',    // Added so you can seed it manually
        'position',
        'name', 
        'email', 
        'password',   // Added because it exists in your screenshot
        'phoneNum', 
        'icNum', 
        'address', 
        'city', 
        'postcode', 
        'state',
        'eme_name',
        'emephoneNum',
        'emerelation',
        'bankName',
        'accountNum',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}