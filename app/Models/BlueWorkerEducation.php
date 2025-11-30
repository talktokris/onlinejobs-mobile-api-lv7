<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlueWorkerEducation extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $table = 'blue_worker_education';
    protected $guarded = ['id'];

    public function education_level_data(){
        return $this->belongsTo(EducationLevel::class, 'education_level');
    }
}