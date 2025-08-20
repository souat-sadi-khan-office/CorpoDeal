<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCarrier extends Model
{
    protected $fillable = [
        'name',
        'transit_time',
        'logo',
        'tracking_url',
        'is_active',
        'free_shipping'
    ];

    public function rules()
    {
        return $this->hasMany(ShippingCarrierRules::class, 'carrier_id');
    }
}