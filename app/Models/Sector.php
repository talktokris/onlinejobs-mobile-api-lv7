<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    public function sub_sectors()
    {
        return $this->hasMany(SubSector::class);
    }
}