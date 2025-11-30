<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartTimeEmployer extends Model
{
    use HasFactory;

    protected $table = 'part_time_employer';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function stateName(){
        return $this->belongsTo(State::class,'state');
    }

    public function cityName(){
        return $this->belongsTo(City::class,'city');
    }

    public function countryName(){
        return $this->belongsTo(Country::class,'country');
    }
}