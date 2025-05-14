<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\League;
use Inertia\Inertia;

class PublicLeagueController extends Controller
{
    public function ranking(Group $group, League $league)
    {
        $players = $league->players()
            ->with('division')
            ->withPivot('points')
            ->orderByDesc('league_player.points')
            ->get();

        return Inertia::render('public/Ranking', [
            'group' => $group,
            'league' => $league,
            'players' => $players,
        ]);
    }
}
