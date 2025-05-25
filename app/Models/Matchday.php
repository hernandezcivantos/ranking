<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matchday extends Model
{
    protected $fillable = [
        'league_id',
        'name',
        'status',
        'round_type',
        'qualified_per_group',
        'sport',
        'sets_per_match',
        'fields_total',
        'description',
        'date',
    ];

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
