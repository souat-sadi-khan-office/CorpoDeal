<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $table = 'zones';

    protected $fillable = [
        'name',
        'status',
        'cost'
    ];

    // relation with product stock
    public function stock()
    {
        return $this->belongsTo(ProductStock::class);
    }
}
