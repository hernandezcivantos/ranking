<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function jugadores()
    {
        return $this->belongsToMany(Player::class, 'grupo_player');
    }
}
