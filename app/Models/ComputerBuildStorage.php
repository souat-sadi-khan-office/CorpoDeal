<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class ComputerBuildStorage extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'builder_id',
        'storage_id',
    ];

    // Define relationships with the products table
    public function build()
    {
        return $this->belongsTo(ComputerBuild::class, 'builder_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'storage_id');
    }
}
