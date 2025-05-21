<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BracketMatch extends Model
{
    protected $fillable = ['bracket_round_id', 'player1_name', 'player2_name', 'winner_name'];

    public function round(): BelongsTo
    {
        return $this->belongsTo(BracketRound::class, 'bracket_round_id');
    }
}

