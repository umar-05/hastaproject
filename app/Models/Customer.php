<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Changed to Authenticatable
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customer';
    protected $primaryKey = 'matricNum';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matricNum',
        'name', 
        'email', 
        'password', 
        'phoneNum',
        'icNum_passport',
        'address', 
        'city', 
        'postcode', 
        'state',
        'collegeAddress',
        'faculty',
        'eme_name',
        'emephoneNum',
        'emerelation',
        'bankName',
        'accountNum',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'matricNum', 'matricNum');
    }

    public function getRouteKeyName()
    {
        return 'matricNum';
    }
}