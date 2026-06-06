<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'destinations';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status'
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
