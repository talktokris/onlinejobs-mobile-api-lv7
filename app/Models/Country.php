<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }
}