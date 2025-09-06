<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiTokenLog extends Model
{
    protected $fillable = [
        'api_token_id', 'method', 'url', 'ip', 'request_data',
    ];

    protected $casts = [
        'request_data' => 'array',
    ];

}
