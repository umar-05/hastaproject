<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'customer_id',
        'fleet_id',
        'reward_id',
        'voucher_code',
        'pickup_date',
        'pickup_time',
        'return_date',
        'return_time',
        'pickup_loc',
        'return_loc',
        'base_price',
        'discount',
        'total_price',
        'deposit',
        'booking_stat',
        'payment_status',
        'deposit_refunded_at',
        'notes',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
        'base_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'deposit' => 'decimal:2',
        'deposit_refunded_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }

    public function getRentalDaysAttribute()
    {
        return $this->pickup_date->diffInDays($this->return_date);
    }

    public function getTotalWithDepositAttribute()
    {
        return $this->total_price + $this->deposit;
    }

    public function scopePending($query)
    {
        return $query->where('booking_stat', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('booking_stat', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('booking_stat', 'completed');
    }
}