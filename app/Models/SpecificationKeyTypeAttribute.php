<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationKeyTypeAttribute extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'admin_id',
        'key_type_id',
        'name',
        'extra',
        'status',
    ];

    // Relationship with Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Relationship with SpecificationKeyType
    public function type()
    {
        return $this->belongsTo(SpecificationKeyType::class);
    }
}
