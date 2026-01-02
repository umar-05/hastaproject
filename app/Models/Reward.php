<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'reward';

    protected $fillable = [
        'company',
        'discount',
        'valid_until',
        'active',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'reward_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)
            ->where('valid_until', '>=', now());
    }
}

