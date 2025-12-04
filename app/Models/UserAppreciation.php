<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAppreciation extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $fillable = [
        'user_id',
        'name',
        'organization',
        'month',
        'year',
    ];
}
