<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matchday extends Model {
    use HasFactory;
    protected $fillable = ['league_id', 'name', 'date'];

    public function league() {
        return $this->belongsTo(League::class);
    }

    public function groups() {
        return $this->hasMany(MatchdayGroup::class);
    }
}
