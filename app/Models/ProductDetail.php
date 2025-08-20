<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'video_provider',
        'video_link',
        'description',
        'current_stock',
        'low_stock_quantity',
        'cash_on_delivery',
        'est_shipping_days',
        'number_of_sale',
        'average_rating',
        'number_of_rating',
        'average_purchase_price',
        'site_title',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'meta_article_tags',
        'meta_script_tags',
        'pc_builder_item',
        'component_type',
        'peri_component_type',
        'points',
        'visitor_counter',
        'shipping_cost',
        'unit'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productStock()
    {
        return $this->hasOne(ProductStock::class, 'product_id', 'product_id');
    }
    
    public function pc_builder()
    {
        return $this->hasOne(PcBuilderItem::class, 'product_id', 'product_id');
    }
}
