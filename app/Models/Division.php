<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
