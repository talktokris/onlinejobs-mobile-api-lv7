<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongTo(User::class);
    }

    public function country_data(){
        return $this->belongsTo(Country::class, 'country');
    }
}