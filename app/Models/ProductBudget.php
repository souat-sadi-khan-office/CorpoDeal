<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class ProductBudget extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'product_id',
        'budget_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'budget_id', 'id');
    }

    public function budget()
    {
        return $this->belongsTo(LaptopFinderBudget::class, 'budget_id');
    }
}
