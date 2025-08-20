<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductScreenSize extends Model
{
    protected $fillable = [
        'product_id',
        'size_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function budget()
    {
        return $this->belongsTo(LaptopFinderScreenSize::class, 'size_id');
    }
}
