<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $table = 'stock_purchases';

    protected $fillable = [
        'product_id',
        'admin_id',
        'currency_id',
        'sku',
        'quantity',
        'purchase_unit_price',
        'purchase_total_price',
        'file',
        'is_sellable',
    ];

    // Define relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function currency()
    {
        // return $this->belongsTo(Currency::class, 'currency_id','currency_id');
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class,'stock_purchase_id');
    }
}
