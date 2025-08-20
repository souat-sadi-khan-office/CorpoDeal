<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'order_id',
        'payment_id',
        'user_id',
        'is_refunded',
        'is_approved',
        'approver_id',
        'approver_message',
        'amount',
        'reason',
        'possible_return_date',
    ];

    // Define the relationships

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approver_id');
    }
}
