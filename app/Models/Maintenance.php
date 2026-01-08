<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenance';
    protected $primaryKey = 'maintenanceID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'maintenanceID',
        'description',
        'mDate',
        'mTime',
        'cost',
        'plateNumber',
    ];

    protected $casts = [
        'mDate' => 'date',
        'cost' => 'decimal:2',
    ];

    /**
     * Relationship to Fleet
     */
    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'plateNumber', 'plateNumber');
    }
}