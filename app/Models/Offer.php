<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $table = 'offers'; // Specify the table name if it's not the plural form of the model name

    protected $fillable = [
        'created_by',
        'name',
        'slug',
        'condition',
        'description',
        'start_date',
        'end_date',
        'type',
        'details',
        'status',
        'site_title',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'meta_article_tag',
        'meta_script_tag',
    ];

    // Define relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
