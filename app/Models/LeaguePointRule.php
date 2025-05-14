<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaguePointRule extends Model
{
    protected $fillable = ['league_id', 'position', 'points'];

    public function league()
    {
        return $this->belongsTo(League::class);
    }
}

