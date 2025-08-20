<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewAnswer extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'review_id',
        'admin_id',
        'comment',
    ];

    // Define relationships
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
