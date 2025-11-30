<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeBookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'user_id',
        'delete_status',
    ];


    public function resume_details(){
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}