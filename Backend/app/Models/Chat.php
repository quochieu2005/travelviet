<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';

    protected $fillable = ['user_id', 'admin_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}