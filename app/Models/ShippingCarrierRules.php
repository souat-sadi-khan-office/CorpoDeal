<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCarrierRules extends Model
{
    protected $fillable = [
        'carrier_id',
        'country_id',
        'rule_type',
        'min_value',
        'max_value',
        'rate',
        'status'
    ];
}
