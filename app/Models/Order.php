<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'unique_id',
        'payment_id',
        'user_id',
        'order_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'exchange_rate',
        'pricing_tier_id',
        'currency_id',
        'payment_status',
        'status',
        'is_delivered',
        'is_cod',
        'is_negative_balance_order',
        'is_admin_order',
        'admin_id',
        'is_refund_requested',
        'refund_type',
    ];

    // Define the relationships
    public function statusHistory()
    {
        return $this->hasOne(OrderStatusHistory::class, 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function details()
    {
        return $this->hasOne(OrderDetail::class, 'order_id');
    }
}
