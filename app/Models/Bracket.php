<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bracket extends Model
{
    protected $fillable = ['matchday_id'];

    public function matchday(): BelongsTo
    {
        return $this->belongsTo(Matchday::class);
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(BracketRound::class);
    }
}

