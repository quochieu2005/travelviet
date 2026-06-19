<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'destination_id',
        'price',
        'oldprice',
        'rating',
        'reviews',
        'tag',
        'image',
        'status'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
