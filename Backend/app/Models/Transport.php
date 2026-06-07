<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'id_destination',
        'mileage',
        'transmission',
        'trips',
        'seats',
        'rating',
        'review',
        'price',
        'image'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'id_destination');
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
}