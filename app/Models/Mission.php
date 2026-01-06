<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    // The table name (optional if it follows Laravel naming conventions)
    protected $table = 'missions';

    // Add this property to "unlock" these columns for the form
    protected $fillable = [
        'title',
        'requirements',
        'description',
        'commission',
        'status',
        'assigned_to',
        'remarks',
    ];
}
