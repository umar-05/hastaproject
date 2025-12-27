<?php

// app/Models/Fleet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $table = 'fleet';

    protected $fillable = [
        'name',
        'plate_number',
        'model',
        'image_url',
        'price_per_day',
        'deposit',
        'status',
        'description',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'fleet_id');
    }

    public function isAvailable($startDate, $endDate)
    {
        return !$this->bookings()
            ->where('booking_stat', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('pickup_date', [$startDate, $endDate])
                    ->orWhereBetween('return_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('pickup_date', '<=', $startDate)
                          ->where('return_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}

// app/Models/Reward.php

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

// app/Models/Voucher.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
        'valid_until',
        'active',
        'usage_limit',
        'times_used',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'active' => 'boolean',
    ];

    public function isValid()
    {
        if (!$this->active) {
            return false;
        }

        if ($this->valid_until < now()) {
            return false;
        }

        if ($this->usage_limit && $this->times_used >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function incrementUsage()
    {
        $this->increment('times_used');
    }
}

// app/Models/Booking.php

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

// app/Models/Customer.php (if you need to create it)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        // Add other customer fields as needed
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }
}