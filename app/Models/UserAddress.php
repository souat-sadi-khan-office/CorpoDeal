<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'user_id',
        'zone_id',
        'country_id',
        'city_id',
        'area',
        'address',
        'postcode',
        'first_name',
        'last_name',
        'company_name',
        'address_line_2',
        'is_default'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Zone
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    // Relationship with Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Relationship with City
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
