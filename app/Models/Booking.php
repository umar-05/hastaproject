<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $table = 'booking';
    protected $primaryKey = 'bookingID';

    protected $fillable = [
        'matricNum',
        'plateNumber',
        'rewardID',
        'destination',
        'pickupDate',
        'returnDate',
        'pickupLoc',
        'returnLoc',
        'deposit',
        'totalPrice',
        'bookingStat',
        'feedback',
    ];

    protected $casts = [
        'pickupDate' => 'datetime',
        'returnDate' => 'datetime',
        'deposit'    => 'double',
        'totalPrice' => 'double',
    ];

    /**
     * Relationships
     */

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'matricNum', 'matricNum');
    }

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'plateNumber', 'plateNumber');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'rewardID', 'rewardID');
    }

    /**
     * Accessors & Scopes
     */

    public function getRentalDaysAttribute()
    {
        if ($this->pickupDate && $this->returnDate) {
            return $this->pickupDate->diffInDays($this->returnDate);
        }
        return 0;
    }

    public function scopePending($query)
    {
        return $query->where('bookingStat', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('bookingStat', 'completed');
    }
}