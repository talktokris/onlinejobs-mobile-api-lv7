<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}