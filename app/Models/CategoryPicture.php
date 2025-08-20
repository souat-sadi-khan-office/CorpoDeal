<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPicture extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'picture',
        'position',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
