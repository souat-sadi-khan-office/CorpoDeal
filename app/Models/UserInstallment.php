<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class UserInstallment extends Model
{
    use IstiyakTraitLog;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'installment_number',
        'payment_date',
        'initial_amount',
        'extra_amount',
        'final_amount',
        'user_id',
        'negative_balance_request_id',
        'is_paid',
        'payment_id',
        'currency_id',
        'paid_by',
        'admin_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payment_date' => 'datetime',
        'is_paid' => 'boolean',
    ];

    /**
     * Relationships
     */

    // User Relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Negative Balance Request Relationship
    public function negativeBalanceRequest()
    {
        return $this->belongsTo(NegativeBalanceRequest::class, 'negative_balance_request_id');
    }

    // Payment Relationship
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    // Currency Relationship
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    // Admin Relationship
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
