<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    protected $table = 'tours';
    protected $fillable = [
        'title', 'slug', 'description', 'short_description', 'price_adult', 
        'price_child', 'price_discount_percent', 'discount_price', 'availability',
        'itinerary', 'start_date', 'end_date', 'max_people', 'duration_days',
        'departure_location', 'destination_id', 'category_id', 'status', 
        'views', 'meta_title', 'meta_description', 'included_services', 'excluded_services'
    ];

    protected $casts = [
        'price_adult' => 'decimal:2',
        'price_child' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'included_services' => 'array',
        'excluded_services' => 'array',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function schedules()
    {
        return $this->hasMany(TourSchedule::class);
    }

    public function images()
    {
        return $this->hasMany(TourImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}