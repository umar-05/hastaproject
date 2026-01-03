<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Import this to allow login
use Illuminate\Notifications\Notifiable;

// FIX: Extend Authenticatable instead of Model so Auth::guard('customer') works
class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    // FIX: Use the plural table name to match standard migrations
    protected $table = 'customers'; 
    
    // FIX: Define the custom primary key
    protected $primaryKey = 'customer_id';

    // FIX: Consolidate the fillable array (I kept the detailed list)
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone_no',    // Make sure your DB column is 'phone_no' (not 'phone')
        'matric_no', 
        'faculty', 
        'ic_no',
        'address', 
        'city', 
        'postcode', 
        'state',
        'college_address',
        'emergency_contact_name',
        'emergency_no',
        'emergency_relation',
        'bank_name', 
        'account_no',
    ];

    // Added to hide sensitive data when converting to array/JSON
    protected $hidden = [
        'remember_token',
    ];

    // Added to ensure password hashing works correctly in newer Laravel versions
    protected $casts = [
        'password' => 'hashed',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }
}