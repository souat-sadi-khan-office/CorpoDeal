<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class ProductPurpose extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'product_id',
        'purpose_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function budget()
    {
        return $this->hasMany(LaptopFinderPurpose::class, 'purpose_id');
    }
}
