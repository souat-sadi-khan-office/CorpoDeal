<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'user_id',
        'trx_id',
        'amount',
        'currency',
        'payer_id',
        'gateway_name',
        'email',
        'status',
        'payment_unique_id',
        'payment_order_id',
    ];

    // Define the relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'payment_id');
    }
}
