<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\IstiyakTraitLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
// class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,IstiyakTraitLog;

    protected $guard = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'currency_id',
        'avatar',
        'last_seen',
        'status',
        'code',
        'latitude',
        'provider_id',
        'provider_name',
        'longitude',
        'email_verified_at',
        'is_premium'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function phones()
    {
        return $this->hasMany(UserPhone::class);
    }

    public function point_history()
    {
        return $this->hasMany(UserPoint::class, 'user_id');
    }

    public function wishlists()
    {
        return $this->hasMany(WishList::class, 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(ContactMessage::class, 'user_id');
    }

    public function coupons()
    {
        return $this->hasMany(UserBroughtCoupon::class, 'user_id');
    }

    public function userCoupon()
    {
        return $this->hasMany(UserCoupon::class, 'user_id');
    }

    public function getCouponAttribute()
    {
        return $this->coupons->belongsTo(Coupon::class, 'coupon_id');
    }

    // public function getUserCouponAttribute()
    // {
    //     return $this->userCoupon->belongsTo(Coupon::class, 'coupon_id');
    // }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relation with currency
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    // Relation with user_phones
    public function phone()
    {
        return $this->hasMany(UserPhone::class);
    }

    // Relation with user_address
    public function address()
    {
        return $this->hasMany(UserAddress::class);
    }

    // Relation with Wallet
    public function wallet()
    {
        return $this->belongsTo(UserWallet::class);
    }

    // Relation with cart
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    // Relation with review
    public function review()
    {
        return $this->hasMany(Reviews::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function negativeBalanceRequest()
    {
        return $this->hasMany(NegativeBalanceRequest::class, 'user_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class, 'user_id');
    }

    public function negetiveBalanceWallets()
    {
        return $this->hasMany(UserNegetiveBalanceWallet::class, 'user_id');
    }

}
