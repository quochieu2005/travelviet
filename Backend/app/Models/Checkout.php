<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
    protected $table = 'checkouts';
    protected $fillable = ['booking_id', 'payment_method', 'amount', 'transaction_id', 'status'];

    protected $casts = ['amount' => 'decimal:2'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}