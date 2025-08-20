<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'activity_id',
        'activity_type',
        'activity',
        'action',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getByAttribute()
    {
        return $this->user?->name ?? $this->admin?->name;
    }

    public function order()
    {
        if ($this->activity_type === 'order') {
            return $this->belongsTo(Order::class, 'activity_id');

        }
        return null;
    }

    // Dynamic relationship with Product model
    public function product()
    {
        if ($this->activity_type === 'product') {
            return $this->belongsTo(Product::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with Category model
    public function category()
    {
        if ($this->activity_type === 'category') {
            return $this->belongsTo(Category::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with Brand model
    public function brand()
    {
        if ($this->activity_type === 'brand') {
            return $this->belongsTo(Brand::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with Cart model
    public function cart()
    {
        if ($this->activity_type === 'cart') {
            return $this->belongsTo(Cart::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with Banner model
    public function banner()
    {
        if ($this->activity_type === 'banner') {
            return $this->belongsTo(Banner::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with Tax model
    public function tax()
    {
        if ($this->activity_type === 'tax') {
            return $this->belongsTo(Tax::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with Rating model
    public function review()
    {
        if ($this->activity_type === 'review') {
            return $this->belongsTo(Rating::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with ProductStock model
    public function stock()
    {
        if ($this->activity_type === 'stock') {
            return $this->belongsTo(ProductStock::class, 'activity_id');
        }

        return null;
    }

    // Dynamic relationship with Question model
    public function question()
    {
        if ($this->activity_type === 'question') {
            return $this->belongsTo(ProductQuestion::class, 'activity_id');
        }

        return null;
    }


    // Dynamic relationship with System model
    public function system()
    {
        if ($this->activity_type === 'system') {
            return $this->belongsTo(ConfigurationSetting::class, 'activity_id');
        }

        return null;
    }

}
