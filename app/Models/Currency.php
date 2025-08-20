<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $table = 'currencies';

    protected $fillable = [
        'country_id',
        'name',
        'symbol',
        'exchange_rate',
        'code',
        'decimal_separator',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
