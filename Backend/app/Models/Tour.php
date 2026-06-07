<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'price_adult',
        'price_child',
        'price_discount_percent',
        'price_child_discount_percent',
        'discount_price',
        'discount_price_child',
        'availability',
        'itinerary',
        'start_date',
        'end_date',
        'max_people',
        'duration_days',
        'departure_location',
        'destination_id',
        'category_id',
        'status',
        'views',
        'meta_title',
        'meta_description',
        'included_services',
        'excluded_services',
    ];

    protected $casts = [
        // Dùng integer thay vì decimal:2 để tránh lỗi locale dấu phẩy/chấm
        'price_adult'                  => 'integer',
        'price_child'                  => 'integer',
        'discount_price'               => 'integer',
        'discount_price_child'         => 'integer',
        'price_discount_percent'       => 'integer',
        'price_child_discount_percent' => 'integer',
        'availability'                 => 'integer',
        'max_people'                   => 'integer',
        'duration_days'                => 'integer',
        'views'                        => 'integer',
        'status'                       => 'boolean',
        'start_date'                   => 'date',
        'end_date'                     => 'date',
        'included_services'            => 'array',
        'excluded_services'            => 'array',
        'itinerary'                    => 'array',
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
