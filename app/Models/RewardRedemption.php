<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    protected $table = 'rewardredemption';

    // Enable timestamps (since we added them in migration)
    public $timestamps = true;

    protected $fillable = [
        'matricNum',
        'rewardID',
        'redemptionDate',
        'status',
        'bookingID'
    ];

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'rewardID', 'rewardID');
    }

    /**
     * Relationships
     */

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'matricNum', 'matricNum');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active')
                     ->whereNull('bookingID'); // Only active if not used yet
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'Used')
                     ->whereNotNull('bookingID');
    }

    /**
     * Check if this redemption is still usable
     */
    public function isUsable()
    {
        // Must be Active and not yet linked to a booking
        if ($this->status !== 'Active' || $this->bookingID !== null) {
            return false;
        }

        // Check if the reward itself is still valid
        return $this->reward && $this->reward->isValidCode();
    }

    /**
     * Mark this voucher as used for a specific booking
     */
    public function markAsUsed($bookingID)
    {
        $this->update([
            'status' => 'Used',
            'bookingID' => $bookingID,
            'used_at' => now()
        ]);
    }


}