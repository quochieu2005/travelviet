<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourSchedule extends Model
{
    use HasFactory;
    protected $table = 'tour_schedules';
    protected $fillable = [
        'tour_id', 'departure_date', 'return_date', 
        'available_slots', 'price_override'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'price_override' => 'decimal:2',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}