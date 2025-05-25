<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMatch extends Model
{
    protected $fillable = [
        'group_id',
        'player1_id',
        'player2_id',
        'sets',
        'status',
        'field_number',
        'is_finished',
    ];

    protected $casts = [
        'sets' => 'array',
        'is_finished' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(MatchdayGroup::class);
    }

    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }
}
