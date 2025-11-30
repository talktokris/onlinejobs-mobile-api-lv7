<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;


    /* --------------- Kris Code Start ---------*/

    public function country_name()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }
    
    /* --------------- Kris Code Start ---------*/

       

    public function graduation_day(){

        return date('d', strtotime($this->graduation_date));

    }

    public function graduation_month(){

        return date('m', strtotime($this->graduation_date));

    }

    public function graduation_year(){

        return date('Y', strtotime($this->graduation_date));

    }
}