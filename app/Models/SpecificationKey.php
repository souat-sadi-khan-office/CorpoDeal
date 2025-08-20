<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationKey extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'admin_id',
        'category_id',
        'name',
        'status',
        'is_public',
        'position',
    ];

    // Relation with admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Relation with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation with types
    public function types()
    {
        return $this->hasMany(SpecificationKeyType::class);
    }
}
