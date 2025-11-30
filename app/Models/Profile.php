<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;


    public function gender_info(){
        return $this->hasOne(Gender::class, 'id', 'gender');
    }

    public function marital_status_info(){
        return $this->hasOne(MaritalStatus::class, 'id', 'marital_status');
    }

    public function religion_info(){
        return $this->hasOne(Religion::class, 'id', 'religion');
    }


    public function user_country()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function religion_data(){
        return $this->belongsTo(Religion::class, 'religion');
    }
    public function nationality_data(){
        return $this->belongsTo(Country::class, 'nationality');
    }
   
    public function native_language_data(){
        return $this->belongsTo(Language::class, 'native_language');
    }
    public function skill_level_data(){
        return $this->belongsTo(SkillLevel::class, 'skill_level');
    }
    public function marital_status_data(){
        return $this->belongsTo(MaritalStatus::class, 'marital_status');
    }
    public function gender_data(){
        return $this->belongsTo(Gender::class, 'gender');
    }
    public function sector()
    {
        return Sector::where('id', $this->sector_id)->first();
    }
    public function sub_sector()
    {
        return SubSector::where('id', $this->sub_sector_id)->first();
    }

    public function agent()
    {
        $agent_profile = AgentProfile::where('agent_code', $this->agent_code)->first();

        return $agent_profile->user;
    }
    //added by milesh 3/23/2020
    public function country_data(){
        return $this->belongsTo(Country::class, 'country');
    }

    public function state_data(){
        return $this->belongsTo(State::class, 'state');
    }
    public function city_data(){
        return $this->belongsTo(City::class, 'city');
    }
}