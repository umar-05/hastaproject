<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $table = 'owner';
    protected $primaryKey = 'ownerIC';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ownerIC',
        'ownerName',
        'ownerEmail',
        'ownerPhoneNum',
        'roadtaxStat',
        'taxActivedate',
        'taxExpirydate',
        'insuranceStat',
        'insuranceActiveDate',
        'insuranceExpirydate',
    ];

    protected $casts = [
        'taxActivedate' => 'date',
        'taxExpirydate' => 'date',
        'insuranceActiveDate' => 'date',
        'insuranceExpirydate' => 'date',
    ];

    /**
     * Relationship to Fleet
     */
    public function fleets()
    {
        return $this->hasMany(Fleet::class, 'ownerIC', 'ownerIC');
    }
}