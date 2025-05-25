<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchdayGroup extends Model
{
    protected $fillable = ['matchday_id', 'name'];

    public function matchday()
    {
        return $this->belongsTo(Matchday::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'matchday_group_player');
    }

    public function matches()
    {
        return $this->hasMany(GroupMatch::class, 'group_id');
    }
}

