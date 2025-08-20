<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class PcBuilderItem extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'product_id',
        'comp_type',
        'cpu_brand',
        'cpu_generation',
        'socket_type',
        'default_tdp',
        'mb_supported_cpu_brand',
        'mb_cpu_generation',
        'mb_supported_socket',
        'mb_form_factor',
        'mb_supported_memory_type',
        'mb_default_tdp',
        'mb_number_of_ram',
        'mb_xmp_support',
        'mb_m2_storage_support',
        'mb_number_of_m2_support',
        'mb_sata_storage_support',
        'mb_number_of_sta_support',
        'mb_lic_support',
        'mb_pcie_slot',
        'ram_memory_type',
        'ram_speed',
        'ram_xmp_support',
        'ram_default_tdp',
        'storage_type',
        'storage_m2_support',
        'storage_sata_support',
        'storage_default_tdp',
        'gc_supported_pcie_slot',
        'gc_supported_form_factor',
        'gc_default_tdp',
        'cc_supported_socket',
        'cc_default_tdp',
        'casing_form_factor',
        'casing_psu_installed',
        'casing_fan_installed',
        'casing_number_of_fan_front',
        'casing_number_of_fan_top',
        'casing_number_of_fan_back',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->belongsTo(ProductDetail::class, 'product_id', 'product_id');
    }
}
