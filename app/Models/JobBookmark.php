<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBookmark extends Model
{
    use HasFactory;


    protected $fillable = [
        'job_id',
        'user_id',
        'delete_status',
    ];


    public function job_details(){
        return $this->hasMany(Job::class, 'id', 'job_id');
    }
}