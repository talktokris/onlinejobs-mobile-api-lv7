<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentProfile extends Model
{
    use HasFactory;

    
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function country_data(){
        return $this->belongsTo(Country::class, 'agency_country');
    }
    public function nationality_data(){
        return $this->belongsTo(Country::class, 'nationality');
    }
    public function company_state_data(){
        return $this->belongsTo(State::class,'agency_state');
    }
    public function company_city_data(){
        return $this->belongsTo(City::class,'agency_city');
    }
}