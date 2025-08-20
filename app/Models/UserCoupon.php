<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'user_id',
        'coupon_id',
        'discount_amount',
    ];

    public function customer()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'coupon_id');
    }
    
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
