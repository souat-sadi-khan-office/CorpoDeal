<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'coupon_code',
        'minimum_shipping_amount',
        'discount_amount',
        'discount_type',
        'maximum_discount_amount',
        'start_date',
        'end_date',
        'status',
        'is_sellable',
        'points_to_buy'
    ];

    public function userBroughtCoupon()
    {
        return $this->belongsTo(UserBroughtCoupon::class, 'coupon_id');
    }
}
