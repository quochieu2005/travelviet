<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'destination_id',
        'rating',
        'reviews',
        'thumbnail',
        'thumbnail_id',
        'facilities',
        'status',
        'views',
    ];

    protected $casts = [
        'facilities' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($hotel) {
            if (empty($hotel->slug)) {
                $hotel->slug = Str::slug($hotel->name);
            }
        });
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
