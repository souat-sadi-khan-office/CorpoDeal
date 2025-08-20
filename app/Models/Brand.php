<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, IstiyakTraitLog;

    protected $fillable = [
        'name',
        'logo',
        'is_featured',
        'status',
        'slug',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'meta_article_tag',
        'meta_script_tag',
        'created_by',
    ];

    // Relationship with Admin (creator)
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // Relationship with BrandType
    public function types()
    {
        return $this->hasMany(BrandType::class);
    }

    // Relation with product
    public function product()
    {
        return $this->hasMany(Brand::class);
    }

}
