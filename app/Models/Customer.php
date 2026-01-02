<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Fix: Match the table name from your migration ('customers')
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
    'name', 'email', 'password', 'phone_no', 'matric_no', 'faculty', 'ic_no',
    'address', 'city', 'postcode', 'state',
    'college_address',
    'emergency_contact_name',
    'emergency_no',
    'emergency_relation',
    'bank_name',
    'account_no',
];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }
}