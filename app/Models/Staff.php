<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';
    protected $primaryKey = 'staffID'; 
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'staffID',
        'position',
        'name',
        'email',
        'password',
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

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'phoneNum'   => 'integer',
        'postcode'   => 'integer',
        'emephoneNum'=> 'integer',
        'accountNum' => 'integer',
    ];
}