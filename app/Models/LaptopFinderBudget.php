<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Model;

class LaptopFinderBudget extends Model
{
    use IstiyakTraitLog;
    protected $fillable = [
        'name',
        'status',
        'created_by'
    ];

    // Relation with admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
