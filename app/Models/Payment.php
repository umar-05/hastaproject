<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $table = 'payment';
    protected $primaryKey = 'paymentID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'paymentID',
        'bookingID',
        'paymentStatus',
        'method',
        'paymentDate',
        'discountedPrice',
        'amount',
        'grandTotal',
    ];

    protected $casts = [
        'paymentDate' => 'datetime',
        'discountedPrice' => 'decimal:2',
        'amount' => 'decimal:2',
        'grandTotal' => 'decimal:2',
    ];

    /**
     * Relationship with Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }
}