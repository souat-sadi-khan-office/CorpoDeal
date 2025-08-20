<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'points',
        'notes',
        'method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
