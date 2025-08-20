<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'product_id',
        'feature_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function budget()
    {
        return $this->belongsTo(LaptopFinderFeatures::class, 'feature_id');
    }
}
