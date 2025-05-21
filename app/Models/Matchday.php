<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matchday extends Model
{
    protected $fillable = ['league_id', 'name', 'status'];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function groups()
    {
        return $this->hasMany(MatchdayGroup::class);
    }

    public function brackets()
    {
        return $this->hasMany(Bracket::class);
    }
}

