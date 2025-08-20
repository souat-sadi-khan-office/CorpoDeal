<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashDeal extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'admin_id',
        'title',
        'short_content',
        'slug',
        'starting_time',
        'deadline_type',
        'deadline_time',
        'image',
        'description',
        'status',
        'site_title',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'meta_article_tag',
        'meta_script_tag',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function type()
    {
        return $this->hasMany(FlashDealType::class);
    }
}
