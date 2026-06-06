<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;


class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'reply',
        'replied_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
