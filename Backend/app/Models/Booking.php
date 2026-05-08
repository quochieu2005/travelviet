<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;


class Booking extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $fillable = [
        'tour_id', 'user_id', 'schedule_id', 'num_adult', 
        'num_children', 'total_price', 'status', 'passengers', 'note'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'passengers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function schedule()
    {
        return $this->belongsTo(TourSchedule::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}