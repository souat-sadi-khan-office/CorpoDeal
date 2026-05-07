<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;


class CouponApiController extends Controller
{
    public function couponCodesApi(Request $request)
    {
        $lifeTimeCoupon = Coupon::where('status', 1)
            ->where('is_sellable', 0)
            ->whereNull('start_date')
            ->get();

        $lifeTimeSellableCoupon = Coupon::where('status', 1)
            ->where('is_sellable', 1)
            ->whereNull('start_date')
            ->get();

        $limitedTimeFreeCoupon = Coupon::where('status', 1)
            ->where('is_sellable', 0)
            ->whereNotNull('start_date')
            ->get();

        $limitedTimeSellableCoupon = Coupon::where('status', 1)
            ->where('is_sellable', 1)
            ->whereNotNull('start_date')
            ->get();

        return response()->json([
            'lifetime_free_coupons' => $lifeTimeCoupon,
            'lifetime_sellable_coupons' => $lifeTimeSellableCoupon,
            'limited_time_free_coupons' => $limitedTimeFreeCoupon,
            'limited_time_sellable_coupons' => $limitedTimeSellableCoupon,
        ]);
    }
}