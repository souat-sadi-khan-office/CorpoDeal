<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'message',
    ];

    // Define relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation with answer
    public function answer()
    {
        return $this->belongsTo(ProductQuestionAnswer::class, 'id', 'question_id');
    }
}
