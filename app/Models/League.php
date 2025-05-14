<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class League extends Model {
    use HasFactory;
    protected $fillable = ['group_id', 'name', 'slug', 'description'];

    protected static function booted()
    {
        static::creating(function ($league) {
            $league->slug = Str::slug($league->name);
        });

        static::updating(function ($league) {
            $league->slug = Str::slug($league->name);
        });
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function matchdays() {
        return $this->hasMany(Matchday::class);
    }

    public function players() {
        return $this->belongsToMany(Player::class)->withPivot('points')->withTimestamps();
    }

    public function pointRules()
    {
        return $this->hasMany(LeaguePointRule::class);
    }

}
