<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';
    protected $fillable = ['username', 'full_name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}