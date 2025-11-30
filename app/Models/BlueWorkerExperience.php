<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlueWorkerExperience extends Model
{
    use HasFactory;

    protected $table = 'blue_worker_experience';
    protected $guarded = ['id'];
    
}