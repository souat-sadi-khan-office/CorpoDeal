<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'suspend_until',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPEND = 'suspend';

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
