<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetiredPersonnelsLanguage extends Model
{
    // Removed HasFactory trait - not available in Laravel 7
    public function language_name(){
        return Language::where('id', $this->language)->first();
    }
}