<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBlueColorApplicant extends Model
{
    use HasFactory;

    protected $table = 'job_blue_worker_applicant';
    protected $guarded = ['id'];
}