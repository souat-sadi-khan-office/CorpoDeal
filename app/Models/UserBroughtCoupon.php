<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBroughtCoupon extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'status'
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }
}
