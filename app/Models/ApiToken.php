<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $fillable = ['name', 'token', 'abilities'];
    
    public function hasAbility($ability) {
        $abilities = $this->abilities ?? ['*'];
        return in_array('*', $abilities) || in_array($ability, $abilities);
    }
}
