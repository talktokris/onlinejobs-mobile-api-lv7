<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Removed HasFactory trait - not available in Laravel 7
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'read_status',
        'status'
    
    ];
}