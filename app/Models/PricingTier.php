<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    protected $fillable = [
        'name',
        'currency_id',
        'discount_type',
        'discount_amount',
        'threshold',
        'with_product_tax',
        'applicable_to',
        'start_date',
        'end_date',
        'description',
        'usage_limit',
        'usage_count',
        'status',
        'admin_id',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

}
