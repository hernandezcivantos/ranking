<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'photo',
        'about',
        'rubber_style',
        'paddle_type',
        'division_id',
        'matches_played',
        'matches_won',
        'matches_lost',
        'score',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_player');
    }
}
