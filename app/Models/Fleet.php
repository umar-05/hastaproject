<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'color',
        'status',
        'image',
        'ownerIC',
        'roadtaxStat',
        'taxActivedate',
        'taxExpirydate',
        'insuranceStat',
        'insuranceActivedate',
        'insuranceExpirydate',
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

    public function maintenances()
    {
        // Assumes the 'maintenance' table has a 'plateNumber' column
        return $this->hasMany(Maintenance::class, 'plateNumber', 'plateNumber');
    }
    
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'ownerIC', 'ownerIC');
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