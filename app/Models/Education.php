<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    public function education_level_data(){
        return $this->belongsTo(EducationLevel::class, 'education_level');
    }
}