<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class RefundTransaction extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'payment_receiver_id',
        'admin_id',
        'refund_id',
        'order_id',
        'payment_method',
        'payment_status',
        'trx_id',
        'amount',
        'currency',
        'to_payer_id',
        'refund_unique_id',
        'refund_order_id',
    ];

    // Define the relationships

    public function paymentReceiver()
    {
        return $this->belongsTo(User::class, 'payment_receiver_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function refundRequest()
    {
        return $this->belongsTo(RefundRequest::class, 'refund_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
