<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMatch extends Model
{
    protected $fillable = ['matchday_group_id', 'player1_name', 'player2_name', 'winner_name'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(MatchdayGroup::class, 'matchday_group_id');
    }
}

