<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlueWorkerEducation extends Model
{
    use HasFactory;

    protected $table = 'blue_worker_education';
    protected $guarded = ['id'];

    public function education_level_data(){
        return $this->belongsTo(EducationLevel::class, 'education_level');
    }
}