<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Jornada;
use Illuminate\Http\Request;

class JornadaPublicController extends Controller
{
    public function show($jornadaId)
    {
        $jornada = Matchday::with([
            'groups.players',
            'groups.matches',
            'brackets.rounds.matches'
        ])->findOrFail($jornadaId);

        return response()->json([
            'groups' => $jornada->groups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'players' => $group->players->map(fn ($p) => [
                        'id' => $p->id,
                        'name' => $p->first_name . ' ' . $p->last_name,
                    ]),
                    'matches' => $group->matches->map(fn ($m) => [
                        'player1' => $m->player1_name,
                        'player2' => $m->player2_name,
                        'winner' => $m->winner_name,
                    ])
                ];
            }),
            'brackets' => $jornada->brackets->flatMap(function ($bracket) {
                return $bracket->rounds->map(function ($round) {
                    return $round->matches->map(fn ($match) => [
                        'player1' => $match->player1_name,
                        'player2' => $match->player2_name,
                        'winner' => $match->winner_name,
                    ]);
                });
            })
        ]);
    }
}
