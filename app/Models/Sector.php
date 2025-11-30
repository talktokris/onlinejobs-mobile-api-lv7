<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    public function sub_sectors()
    {
        return $this->hasMany(SubSector::class);
    }
}