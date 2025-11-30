<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalProfile extends Model
{
    // Removed HasFactory trait - not available in Laravel 7


    protected $casts = [
        'dob' => 'date'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function country_data(){
        return $this->belongsTo(Country::class, 'country');
    }
    public function dob_day(){

        if($this->dob != null){
            return date('d', strtotime($this->dob));
        }

    }

    public function job_seeker_country_data(){
        return $this->belongsTo(Country::class, 'country');
    }
    public function job_seeker_state_data(){
        return $this->belongsTo(State::class,'state');
    }
    public function job_seeker_city_data(){
        return $this->belongsTo(City::class,'city');
    }
    public function job_seeker_job_category_data(){
        return $this->belongsTo(Option::class, 'job_category');
    }
    public function dob_month(){

        if($this->dob != null){
            return date('m', strtotime($this->dob));
        }

    }

    public function dob_year(){

        if($this->dob != null){
            return date('Y', strtotime($this->dob));
        }

    }

    public function age()
    {
        if($this->dob != ''){
            return $this->dob->diff(Carbon::now())->format('%y');
        }else{
            return 'N/A';
        }
        
    }
    
}