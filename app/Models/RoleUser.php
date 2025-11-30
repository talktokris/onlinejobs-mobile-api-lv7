<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $table= "role_user";
    protected $primaryKey= "role_id";
}