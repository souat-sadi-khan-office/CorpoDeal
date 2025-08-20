<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'coupon_id',
        'product_ids', // Assuming this is a string of IDs or JSON
        'details',     // Assuming this is a JSON
        'notes',
        'shipping_method',
        'shipping_address',
        'billing_address',
        'phone',
        'email',
        'refunded_product_ids',
        'refunded_details',
        'tier_info'
    ];

    // Define the relationships

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function stocks()
    {
        $details = json_decode($this->details, true);

        if (isset($details['products']) && is_array($details['products'])) {
            $stockIds = collect($details['products'])->pluck('stock_id')->toArray();

            return ProductStock::whereIn('id', $stockIds);
        }
        return ProductStock::whereRaw('1 = 0');
    }

    public function getProductsAttribute()
    {
        $details = json_decode($this->attributes['details'], true);
        if (!$details || !isset($details['products'])) {
            return collect();
        }

        return collect($details['products'])->map(function ($product) {
            return (object)$product;
        });
    }


    public function relatedProducts()
    {
        $productIds = $this->products->pluck('id')->toArray();

        if (empty($productIds)) {
            return collect();
        }

        return Product::whereIn('id', $productIds)->get();
    }

    // Scope to filter order details based on optional parameters
    public function scopeFilterByOrderAttributes($query, $filters)
    {
        return $query->when(isset($filters['payment_status']), function ($query) use ($filters) {
            $query->whereHas('order', function ($q) use ($filters) {
                $q->where('payment_status', $filters['payment_status']);
            });
        })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->where('status', $filters['status']);
                });
            })
            ->when(isset($filters['is_cod']), function ($query) use ($filters) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->where('is_cod', $filters['is_cod']);
                });
            })
            ->when(isset($filters['is_refund_requested']), function ($query) use ($filters) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->where('is_refund_requested', $filters['is_refund_requested']);
                });
            });
    }

}
