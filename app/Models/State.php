<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function city()
    {
        return $this->hasMany(City::class);
    }
    public function EmployerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
    }
}