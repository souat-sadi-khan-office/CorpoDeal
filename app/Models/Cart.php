<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'user_id',
        'ip',
        'total_price',
        'total_quantity',
        'currency_id',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation with currency
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function details()
    {
        return $this->hasMany(CartDetail::class, 'cart_id');
    }
}
