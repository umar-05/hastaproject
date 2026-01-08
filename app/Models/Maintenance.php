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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->maintenanceID)) {
                // Generates a random string ID like "M-659A2B"
                $model->maintenanceID = 'M-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    /**
     * Relationship to Fleet
     */
    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'plateNumber', 'plateNumber');
    }
}