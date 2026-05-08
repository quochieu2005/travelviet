<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class EmailLog extends Model
{
    use HasFactory;
    protected $table = 'email_logs';
    protected $fillable = [
        'user_id', 'booking_id', 'type', 'subject', 
        'content', 'status', 'error_msg', 'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}