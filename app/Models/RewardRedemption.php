<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    // Specify the table name since it's not standard plural
    protected $table = 'rewardredemption';

    // Disable timestamps if your table doesn't have created_at/updated_at columns
    public $timestamps = false;

    // Primary keys (Usually a combination of matricNum and rewardID)
    protected $fillable = [
        'matricNum',
        'rewardID',
        'redemptionDate',
    ];

    // Relationship: Link back to the Reward details (to get voucherCode, etc.)
    public function reward()
    {
        return $this->belongsTo(Reward::class, 'rewardID', 'rewardID');
    }
}