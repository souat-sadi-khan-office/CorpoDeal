<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class WalletTopup extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'wallet_id',
        'gateway_name',
        'amount',
        'is_admin_paid',
        'admin_id',
    ];

    // Define the relationships

    public function wallet()
    {
        return $this->belongsTo(UserWallet::class, 'wallet_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
