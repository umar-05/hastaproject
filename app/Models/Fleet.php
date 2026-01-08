<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $table = 'fleet';
    protected $primaryKey = 'plateNumber';
    
    // Since your primary key is a string (e.g., "VHJ8821"), we must tell Laravel
    public $incrementing = false;
    protected $keyType = 'string';

    // *** CRITICAL: All fields you want to save MUST be listed here ***
    protected $fillable = [
        'plateNumber',
        'modelName',
        'year',
        'color',
        'price',
        'status',
        'ownerIC',
        
        // Gallery Photos
        'photo1',
        'photo2',
        'photo3',

        // Road Tax Details
        'roadtaxStat',
        'roadtaxActiveDate',
        'roadtaxExpiryDate',
        'roadtaxFile',

        // Insurance Details
        'insuranceStat',
        'insuranceActiveDate',
        'insuranceExpiryDate',
        'insuranceFile',

        // Grant
        'grantFile',
        
        'note'
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'ownerIC', 'ownerIC');
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class, 'plateNumber', 'plateNumber');
    }
}