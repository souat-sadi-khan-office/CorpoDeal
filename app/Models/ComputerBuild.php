<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class ComputerBuild extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'user_id',
        'processor_id',
        'cpu_cooler_id',
        'mother_board_id',
        'graphics_card_id',
        'psu_id',
        'casing_id',
        'monitor_id',
        'keyboard_id',
        'mouse_id',
        'anti_virus_id',
        'head_phone_id',
        'ups_id'
    ];

    public function ram()
    {
        return $this->hasMany(ComputerBuildRam::class, 'builder_id');
    }

    public function storage()
    {
        return $this->hasMany(ComputerBuildStorage::class, 'builder_id');
    }

    public function fan()
    {
        return $this->hasMany(ComputerBuildFan::class, 'builder_id');
    }

    // Define relationships with the products table
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(Product::class, 'processor_id');
    }

    public function cpu_cooler()
    {
        return $this->belongsTo(Product::class, 'cpu_cooler_id');
    }

    public function casing()
    {
        return $this->belongsTo(Product::class, 'casing_id');
    }

    public function graphics_card()
    {
        return $this->belongsTo(Product::class, 'graphics_card_id');
    }

    public function motherBoard()
    {
        return $this->belongsTo(Product::class, 'mother_board_id');
    }

    public function psu()
    {
        return $this->belongsTo(Product::class, 'psu_id');
    }

    public function monitor()
    {
        return $this->belongsTo(Product::class, 'monitor_id');
    }

    public function keyboard()
    {
        return $this->belongsTo(Product::class, 'keyboard_id');
    }

    public function mouse()
    {
        return $this->belongsTo(Product::class, 'mouse_id');
    }

    public function anti_virus()
    {
        return $this->belongsTo(Product::class, 'anti_virus_id');
    }

    public function headphone()
    {
        return $this->belongsTo(Product::class, 'head_phone_id');
    }

    public function ups()
    {
        return $this->belongsTo(Product::class, 'ups_id');
    }
}
