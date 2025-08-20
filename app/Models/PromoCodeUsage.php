<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCodeUsage extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'promo_code_id',
        'user_id',
    ];

    // Define relationships
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
