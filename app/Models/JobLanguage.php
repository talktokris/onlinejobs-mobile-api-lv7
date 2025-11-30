<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLanguage extends Model
{
    use HasFactory;

    public function language_data(){

        return $this->belongsTo(Language::class, 'language');
        
    }


    public function language_name(){

        return $this->belongsTo(Language::class,'id', 'skill_id');
        
    }
}