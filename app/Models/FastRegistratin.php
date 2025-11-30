<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FastRegistratin extends Model
{
    use HasFactory;

    protected $table = 'blue_worker_registration';
    protected $guarded = ['id'];
    // protected $casts = [
    //     'dob' => 'date'
    // ];

    public function state(){
        return $this->belongsTo(State::class, 'state_id');
    }
    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function dob_day(){

        if($this->dob != null){
            return date('d', strtotime($this->dob));
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

    public function blue_worker_experience(){
        return $this->hasMany(BlueWorkerExperience::class, 'blue_worker_registration_id');
    }

    public function blue_worker_education(){
        return $this->hasMany(BlueWorkerEducation::class, 'blue_worker_registration_id');
    }

}