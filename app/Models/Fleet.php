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
    protected $guarded = [];

    protected $fillable = [
        'plateNumber',
        'modelName',
        'year',
        'photo1',
        'photo2',
        'photo3',
        'ownerName',
        'ownerIc',
        'ownerPhone',
        'ownerEmail',
        'roadtaxStat',
        'roadtaxActiveDate',
        'roadtaxExpiryDate',
        'insuranceStat',
        'insuranceActiveDate',
        'insuranceExpiryDate',
        'status',
        'note',
        'matricNum',
        'staffID',
    ];

    protected $casts = [
        'year' => 'integer',
        'roadtaxActiveDate' => 'date',
        'roadtaxExpiryDate' => 'date',
        'insuranceActiveDate' => 'date',
        'insuranceExpiryDate' => 'date',
    ];

    /**
     * Relationships
     */
    public function maintenance()
    {
        // Parameter 2: Foreign Key (name of the column in the 'maintenances' table)
        // Parameter 3: Local Key (name of the column in this 'fleet' table)
        return $this->hasMany(Maintenance::class, 'plateNumber', 'plateNumber'); 
    }
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