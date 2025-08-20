<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory,IstiyakTraitLog;
    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'email',
        'rating',
        'review',
        'files',
        'status'
    ];


       // Define the relationship to the Product model
       public function product()
       {
           return $this->belongsTo(Product::class);
       }

       // Define the relationship to the User model
       public function user()
       {
           return $this->belongsTo(User::class);
       }

       // A method to get a human-readable rating
       public function getFormattedRatingAttribute()
       {
           return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
       }
}
