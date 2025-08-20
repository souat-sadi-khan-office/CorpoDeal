<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class UserNegetiveBalanceWallet extends Model
{
    use IstiyakTraitLog;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'user_id',
        'current_balance',
        'used_balance',
        'status',
        'frozen_until',
    ];


    /**
     * Relationships
     */

    // User Relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Currency Relationship
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
