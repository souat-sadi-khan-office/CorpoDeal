<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'admin_id',
        'type',
        'code',
        'details',
        'discount',
        'discount_type',
        'start_date',
        'end_date',
    ];

    // Define relationship
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

}
