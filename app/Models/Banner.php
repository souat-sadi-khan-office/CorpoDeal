<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'banner_type',
        'name',
        'header_title',
        'old_offer',
        'new_offer',
        'image',
        'source_type',
        'source_id',
        'link',
        'alt_tag',
        'created_by',
        'status',
    ];

    // Define relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // You can add relationships for products and categories as needed
    public function product()
    {
        return $this->belongsTo(Product::class, 'source_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'source_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'source_id');
    }

}
