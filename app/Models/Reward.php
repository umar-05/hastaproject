<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'claimedCount',
        'expiryDate',
        'rewardStatus'
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

    public function redemptions()
    {
        // A reward can be redeemed many times by different customers
        return $this->hasMany(RewardRedemption::class, 'rewardID', 'rewardID');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('rewardStatus', 'Active')
                     ->where('expiryDate', '>=', now());
    }

    /**
     * Validation Logic using YOUR attributes
     */
    public function isValidCode()
    {
        // 1. Check Status
        if (strtolower($this->rewardStatus) !== 'active') {
            return false;
        }

        // 2. Check Expiry
        if ($this->expiryDate && Carbon::now()->gt($this->expiryDate)) {
            return false;
        }

        // 3. Check Limit
        // If totalClaimable is 0, we assume it's unlimited. 
        // If > 0, we check if claimedCount has reached the limit.
        if ($this->totalClaimable > 0 && $this->claimedCount >= $this->totalClaimable) {
            return false;
        }

        return true;
    }

    public function incrementUsage()
    {
        $this->increment('claimedCount');
    }
}