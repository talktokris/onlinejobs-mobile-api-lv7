<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicant extends Model
{
    // Removed HasFactory trait - not available in Laravel 7



    public function job_details(){
        return $this->hasMany(Job::class, 'id', 'job_id');
    }

    public function jobseeker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function applicantProfile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }


    public function applicantUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}