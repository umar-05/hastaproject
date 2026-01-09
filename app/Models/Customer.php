<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Changed to Authenticatable
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customer';
    protected $primaryKey = 'matricNum';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matricNum',
        'name', 
        'email', 
        'password', 
        'phoneNum',
        'icNum_passport',
        'address', 
        'city', 
        'postcode', 
        'state',
        'collegeAddress',
        'faculty',
        'eme_name',
        'emephoneNum',
        'emerelation',
        'bankName',
        'accountNum',
        'doc_ic_passport',
        'doc_license',
        'doc_matric',
        'accStatus',
        'blacklistReason',
        'rewardPoints',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'matricNum', 'matricNum');
    }

    public function getRouteKeyName()
    {
        return 'matricNum';
    }

    public function calculateStamps()
    {
        // Count only approved bookings
        $approvedBookings = $this->bookings()
            ->where('bookingStatus', 'Approved') // or whatever status means "approved"
            ->count();

        return $approvedBookings;
    }

    /**
     * Get current available stamps (total earned - total spent)
     */
    public function getAvailableStampsAttribute()
    {
        $totalEarned = $this->calculateStamps();
        $totalSpent = $this->redemptions()->sum('reward.rewardPoints');
        
        return max(0, $totalEarned - $totalSpent);
    }

    /**
     * Get active (unused) vouchers
     */
    public function getActiveVouchersAttribute()
    {
        return $this->redemptions()
            ->where('status', 'Active')
            ->whereNull('bookingID')
            ->with('reward')
            ->get();
    }

    /**
     * Get used vouchers
     */
    public function getUsedVouchersAttribute()
    {
        return $this->redemptions()
            ->where('status', 'Used')
            ->whereNotNull('bookingID')
            ->with('reward', 'booking')
            ->get();
    }

    /**
     * Check if customer can claim a reward
     */
    public function canClaimReward(Reward $reward)
    {
        return $this->available_stamps >= $reward->rewardPoints;
    }
    
}