<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchdayGroup extends Model {
    use HasFactory;
    protected $fillable = ['matchday_id', 'name'];

    public function matchday() {
        return $this->belongsTo(Matchday::class);
    }

    public function players() {
        return $this->belongsToMany(Player::class, 'matchday_group_player')
            ->withPivot('position', 'points')
            ->withTimestamps();
    }
}
