<?php

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

