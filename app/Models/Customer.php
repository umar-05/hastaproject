<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        // Add other customer fields as needed
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }
}

