<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use IstiyakTraitLog;

    protected $fillable = [
        'order_id',
        'user_id',
        'subject',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
