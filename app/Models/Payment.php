<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // 1. Define the table name (since it's not 'payments')
    protected $table = 'payment';

    // 2. Define the Primary Key
    protected $primaryKey = 'paymentID';

    // 1. IMPORTANT: Tell Laravel this key is NOT auto-incrementing
    public $incrementing = false;
    
    // 2. IMPORTANT: Tell Laravel this key is a String
    protected $keyType = 'string';

    // 3. Allow mass assignment for these columns
    protected $fillable = [
        'paymentID',
        'bookingID',
        'paymentStatus', // pending, paid, failed
        'method',        // bank_transfer, duitnow
        'paymentDate',
        'amount',        // Original price
        'discountedPrice', // Amount deducted (optional) or Price after discount
        'grandTotal',    // Final amount paid
        'receipt_path',  // Note: You might need to add this column to your DB or save it in bookings
    ];

    // Relationship back to Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'bookingID', 'bookingID');
    }

}