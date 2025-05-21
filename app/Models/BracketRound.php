<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BracketRound extends Model
{
    protected $fillable = ['bracket_id', 'number'];

    public function bracket(): BelongsTo
    {
        return $this->belongsTo(Bracket::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(BracketMatch::class);
    }
}

