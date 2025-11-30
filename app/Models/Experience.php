<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    public function user(){
        return $this->belongTo(User::class);
    }

    public function country_data(){
        return $this->belongsTo(Country::class, 'country');
    }
}