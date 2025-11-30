<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlueWorkerExperience extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $table = 'blue_worker_experience';
    protected $guarded = ['id'];
    
}