<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Removed HasFactory trait - not available in Laravel 7
    protected $fillable = [
        'user_id',
        'sender_id',
        'receiver_id',
        'thread_id',
        'parent_message_id',
        'message_type',
        'job_id',
        'title',
        'message',
        'read_status',
        'status'
    ];

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the job associated with the message
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}