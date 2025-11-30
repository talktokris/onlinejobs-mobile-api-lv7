<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function user_profile(){
        return $this->belongsTo(UserProfile::class);
    }
    public function country_data(){
        return $this->belongsTo(Country::class, 'country');
    }
    public function company_country_data(){
        return $this->belongsTo(Country::class, 'company_country');
    }

    public function company_state_data(){
        return $this->belongsTo(State::class, 'state');
    }
    public function company_city_data(){
        return $this->belongsTo(City::class, 'company_city');
    }

    public function offers(){
        return $this->hasMany(Offer::class, 'employer_id', 'user_id');
    }

    public function hireCount()
    {
        $count = 0;
        foreach($this->offers as $offer){
            $count += $offer->applicants->where('status', 3)->count();
        }

        return $count;
    }
}