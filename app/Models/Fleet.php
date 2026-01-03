<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $table = 'fleet';
    protected $primaryKey = 'plateNumber';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'plateNumber',
        'modelName',
        'year',
        'photos',
        'ownerName',
        'ownerIc',
        'ownerPhone',
        'ownerEmail',
        'roadtaxStat',
        'taxActivedate',
        'taxExpirydate',
        'insuranceStat',
        'insuranceActivedate',
        'insuranceExpirydate',
        'status',
        'note',
        'matricNum',
        'staffID',
    ];

    protected $casts = [
        'year' => 'integer',
        'taxActivedate' => 'date',
        'taxExpirydate' => 'date',
        'insuranceActivedate' => 'date',
        'insuranceExpirydate' => 'date',
    ];

    /**
     * Relationships
     */

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'plateNumber', 'plateNumber');
    }

    /**
     * Availability Logic
     */
    public function isAvailable($startDate, $endDate)
    {
        return !$this->bookings()
            ->where('bookingStat', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('pickupDate', [$startDate, $endDate])
                    ->orWhereBetween('returnDate', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('pickupDate', '<=', $startDate)
                          ->where('returnDate', '>=', $endDate);
                    });
            })
            ->exists();
    }
}