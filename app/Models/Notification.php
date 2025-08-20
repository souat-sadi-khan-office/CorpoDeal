<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'admin_read_at',
        'user_read_at',
        'go_to_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
