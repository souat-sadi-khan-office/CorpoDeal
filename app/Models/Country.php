<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $table = 'countries';

    protected $fillable = [
        'zone_id',
        'name',
        'image',
        'status',
        'cost',
    ];

    // Relationship with Zone
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    // Relationship with City
    public function city()
    {
        return $this->hasMany(City::class);
    }

    // Relationship with product stock
    public function stock()
    {
        return $this->belongsTo(ProductStock::class);
    }
}
