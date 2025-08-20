<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandType extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'brand_id',
        'name',
        'status',
        'is_featured',
        'related_categories',
    ];

    // Relationship with Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Relation with product
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
