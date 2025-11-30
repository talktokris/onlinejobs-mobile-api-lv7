<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}