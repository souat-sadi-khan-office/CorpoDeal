<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestionAnswer extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'question_id',
        'admin_id',
        'message',
    ];

    // Define relationships
    public function question()
    {
        return $this->belongsTo(ProductQuestion::class, 'question_id', 'id');
    }

    // Relation with admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

}
