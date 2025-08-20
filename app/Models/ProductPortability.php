<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPortability extends Model
{
    protected $fillable = [
        'product_id',
        'portable_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function budget()
    {
        return $this->belongsTo(LaptopFinderPortability::class, 'portable_id');
    }
}
