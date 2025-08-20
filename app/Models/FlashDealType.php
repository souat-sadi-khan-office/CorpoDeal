<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashDealType extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_deal_id',
        'product_id',
        'discount_amount',
        'discount_type',
    ];

    // Relation with flashDeal
    public function flashDeal()
    {
        return $this->belongsTo(FlashDeal::class, 'flash_deal_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productDetails()
    {
        return $this->belongsTo(ProductDetail::class, 'product_id','product_id');
    }

}
