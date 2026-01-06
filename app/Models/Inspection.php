<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspection';
    protected $primaryKey = 'inspectionID';
    public $incrementing = false; // Important because your ID is a String
    protected $keyType = 'string';

    protected $fillable = [
        'inspectionID',
        'bookingID',
        'type',
        'frontViewImage',
        'backViewImage',
        'leftViewImage',
        'rightViewImage',
        'interior1Image',
        'interior2Image',
        'mileage',
        'signature',
        'fuelImage',
        'fuelBar',
        'dateOut',
        'time',
        'pic',
        'remark'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }
}
