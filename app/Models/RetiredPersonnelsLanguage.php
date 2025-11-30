<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetiredPersonnelsLanguage extends Model
{
    use HasFactory;
    public function language_name(){
        return Language::where('id', $this->language)->first();
    }
}