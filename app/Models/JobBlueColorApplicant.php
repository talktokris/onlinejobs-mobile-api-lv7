<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBlueColorApplicant extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $table = 'job_blue_worker_applicant';
    protected $guarded = ['id'];
}