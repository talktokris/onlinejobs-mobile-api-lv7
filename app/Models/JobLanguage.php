<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLanguage extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $fillable = [
        'user_id',
        'job_id',
        'language',
        'speaking',
        'reading',
        'writing',
    ];

    public function language_data(){

        return $this->belongsTo(Language::class, 'language');
        
    }


    public function language_name(){

        return $this->belongsTo(Language::class,'id', 'skill_id');
        
    }
}