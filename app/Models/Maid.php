<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maid extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $table = 'maid';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function company_country_data(){
        return $this->belongsTo(Country::class, 'country');
    }
    public function company_state_data(){
        return $this->belongsTo(State::class,'state');
    }
    public function company_city_data(){
        return $this->belongsTo(City::class,'city');
    }
    public function gender_data(){
        return $this->belongsTo(Gender::class, 'gender');
    }
    public function marital_status_data(){
        return $this->belongsTo(MaritalStatus::class, 'marital_status');
    }
    public function religion_data(){
        return $this->belongsTo(Religion::class, 'religion');
    }
}