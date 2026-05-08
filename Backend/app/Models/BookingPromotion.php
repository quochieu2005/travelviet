<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPromotion extends Model
{
    use HasFactory;

    protected $table = 'booking_promotion';

    protected $fillable = ['booking_id', 'promotion_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}