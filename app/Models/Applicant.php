<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    public function offer()
    {

        return $this->belongsTo(Offer::class);

    }

    public function gw_dm()
    {

        return $this->belongsTo(User::class, 'user_id');

    }

    public function applicantProfile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }

    public function applicantUser()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}