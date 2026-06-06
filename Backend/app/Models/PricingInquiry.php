<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingInquiry extends Model
{
    protected $table = 'pricing_inquiries';

    protected $fillable = [
        'pricing_plan_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_id');
    }
}
