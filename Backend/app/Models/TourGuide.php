<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourGuide extends Model
{
    use HasFactory;
    protected $table = 'tour_guides';
    protected $fillable = ['name', 'bio', 'avatar', 'languages', 'phone', 'is_active'];

    public function assignments()
    {
        return $this->hasMany(TourGuideAssignment::class);
    }
}