<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class NegativeBalanceRequest extends Model
{
    use IstiyakTraitLog;

    protected $fillable = [
        'amount',
        'user_id',
        'installment_plan_id',
        'document',
        'document_2',
        'document_3',
        'description',
        'is_approved',
        'is_declined',
        'admin_id',
        'currency_id'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_declined' => 'boolean',
        'document_3' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
