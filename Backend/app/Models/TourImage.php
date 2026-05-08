<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourImage extends Model
{
    use HasFactory;
    protected $table = 'tour_images';
    protected $fillable = ['tour_id', 'image', 'type', 'sort_order'];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}