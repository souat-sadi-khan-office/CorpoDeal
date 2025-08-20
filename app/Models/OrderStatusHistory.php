<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id',
        'pending_time',
        'packaging_time',
        'shipping_time',
        'out_for_delivery_time',
        'delivered_time',
        'returned_time',
        'failed_time'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
