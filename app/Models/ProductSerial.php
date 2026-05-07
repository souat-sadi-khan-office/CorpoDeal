<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    protected $fillable = ['serial', 'supplier_id', 'stock_purchase_id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    public function stock()
    {
        return $this->belongsTo(StockPurchase::class,'stock_purchase_id');
    }
}

