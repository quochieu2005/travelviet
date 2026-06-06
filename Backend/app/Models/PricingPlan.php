<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $table = 'pricing_plans';

    protected $fillable = [
        'name', 'description', 'price', 'price_note',
        'features', 'disabled_features',
        'is_popular', 'button_text', 'order', 'status',
    ];

    protected $casts = [
        'features'          => 'array',
        'disabled_features' => 'array',
        'is_popular'        => 'boolean',
    ];

    public function inquiries()
    {
        return $this->hasMany(PricingInquiry::class);
    }
}