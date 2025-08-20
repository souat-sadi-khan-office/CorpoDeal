<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'name',
        'creator_id',
        'length',
        'extra_charge_percent',
        'status'
    ];

    protected $casts = [
        'extra_charge_percent' => 'integer',
    ];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'creator_id');
    }

    public function negativeBalanceRequests()
    {
        return $this->hasMany(NegativeBalanceRequest::class);
    }
}
