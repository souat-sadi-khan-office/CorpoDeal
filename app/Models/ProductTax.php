<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTax extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'product_id',
        'tax_id',
        'tax',
        'tax_type'
    ];

    // Define relationship
    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function tax_model()
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }
}
