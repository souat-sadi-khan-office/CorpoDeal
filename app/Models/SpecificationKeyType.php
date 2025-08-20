<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationKeyType extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'admin_id',
        'specification_key_id',
        'name',
        'status',
        'position',
        'show_on_filter',
        'filter_name',
    ];

    // Relationship with Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Relationship with SpecificationKey
    public function specificationKey()
    {
        return $this->belongsTo(SpecificationKey::class);
    }

    // Relationship with attributes
    public function attributes()
    {
        return $this->hasMany(SpecificationKeyTypeAttribute::class,'key_type_id');
    }
}
