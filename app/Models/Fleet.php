<?php

// app/Models/Fleet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $table = 'fleet';
    protected $primaryKey = 'fleet_id';

    protected $fillable = [
        'name',
        'plate_number',
        'model',
        'image_url',
        'price_per_day',
        'deposit',
        'status',
        'description',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'fleet_id');
    }

    public function isAvailable($startDate, $endDate)
    {
        return !$this->bookings()
            ->where('booking_stat', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('pickup_date', [$startDate, $endDate])
                    ->orWhereBetween('return_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('pickup_date', '<=', $startDate)
                          ->where('return_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}