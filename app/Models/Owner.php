<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $table = 'owner';
    protected $primaryKey = 'ownerIC';
    public $incrementing = false; // Because IC is a string
    protected $keyType = 'string';

    protected $fillable = [
        'ownerIC',
        'ownerName',
        'ownerEmail',
        'ownerPhoneNum',
    ];
}