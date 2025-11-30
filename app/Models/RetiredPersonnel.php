<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetiredPersonnel extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function company_state_data(){
        return $this->belongsTo(State::class,'state');
    }

    public function company_country_data(){
        return $this->belongsTo(Country::class, 'country');
    }

    public function retired_nationality_data(){
        return $this->belongsTo(Country::class, 'nationality');
    }

    public function retired_job_category_data(){
        return $this->belongsTo(Option::class, 'job_category');
    }

    public function company_city_data(){
        return $this->belongsTo(City::class,'city');
    }
}