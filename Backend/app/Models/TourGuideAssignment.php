<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourGuideAssignment extends Model
{
    use HasFactory;
    protected $table = 'tour_guide_assignments';
    protected $fillable = [
        'tour_id', 'tour_guide_id', 'schedule_id', 
        'assigned_date', 'role'
    ];

    protected $casts = [
        'assigned_date' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function tourGuide()
    {
        return $this->belongsTo(TourGuide::class);
    }

    public function schedule()
    {
        return $this->belongsTo(TourSchedule::class);
    }
}