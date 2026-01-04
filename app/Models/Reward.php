<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'reward';
    protected $primaryKey = 'rewardID';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'rewardID', 
        'rewardPoints', 
        'voucherCode', 
        'rewardType', 
        'rewardAmount', 
        'totalClaimable', 
        'expiryDate', 
        'rewardStatus',
    ];

    protected $casts = [
        'rewardPoints'   => 'integer',
        'rewardAmount'   => 'integer',
        'totalClaimable' => 'integer',
        'expiryDate'     => 'date',
    ];

    /**
     * Relationships
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'rewardID', 'rewardID');
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'rewardmanagement', 'rewardID', 'staffID');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('rewardStatus', 'Active')
                     ->where('expiryDate', '>=', now());
    }
}