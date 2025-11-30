<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    public function education_level_data(){
        return $this->belongsTo(EducationLevel::class, 'education_level');
    }
}