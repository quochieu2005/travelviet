<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPasswordNotification;
class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;


    protected $table = 'admins';
    protected $fillable = ['username', 'full_name', 'email', 'password', 'role', 
    'status' , 'avatar' , 'avatar_id' , 'slug' , 'phone' , 
    'created_at' , 'updated_at' , 'remember_token'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
}