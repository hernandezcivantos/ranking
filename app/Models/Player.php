<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'photo',
        'about',
        'rubber_style',
        'paddle_type',
        'matches_played',
        'matches_won',
        'matches_lost',
        'division_id',
        'club_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function leagues()
    {
        return $this->belongsToMany(League::class)->withPivot('points')->withTimestamps();
    }

    public function matchdayGroups()
    {
        return $this->belongsToMany(MatchdayGroup::class, 'matchday_group_player')
            ->withPivot('position', 'points')
            ->withTimestamps();
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
