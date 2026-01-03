<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        // Standard Identity
        'name', 
        'email', 
        'matric_number', // Added
        'faculty',       // Added
        'position',      // Kept (Specific to Staff)
        
        // Profile Fields (Now matching User table names)
        'phoneNum',      // Renamed from phone_no
        'icNum',         // Renamed from ic_no
        
        // Address
        'address', 
        'city', 
        'postcode', 
        'state',
        'collegeAddress', // Added
        'eme_name',
        'emephoneNum',
        'emerelation',
        'bankName',
        'accountNum',
        'reward_points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'email';
    }

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