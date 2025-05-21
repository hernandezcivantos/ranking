<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matchday;
use App\Models\MatchdayGroup;
use App\Models\Player;
use App\Models\GroupMatch;
use App\Models\Bracket;
use App\Models\BracketRound;
use App\Models\BracketMatch;

class MatchdaySeeder extends Seeder
{
    public function run(): void
    {
        // Crear jornada
        $matchday = Matchday::create([
            'league_id' => 1,
            'name' => 'Jornada de prueba',
            'status' => 'en_marcha'
        ]);

        // Crear 2 grupos con 3 jugadores ficticios cada uno
        foreach (['A', 'B'] as $suffix) {
            $group = MatchdayGroup::create([
                'matchday_id' => $matchday->id,
                'name' => "Grupo $suffix"
            ]);

            $players = collect(['Jugador1', 'Jugador2', 'Jugador3'])->map(function ($base) use ($suffix) {
                return Player::firstOrCreate(
                    [
                        'first_name' => $base,
                        'last_name' => $suffix
                    ],
                    [
                        'division_id' => 1
                    ]
                );
            });

            $group->players()->sync($players->pluck('id'));

            // Crear partidos en el grupo usando nombre completo
            GroupMatch::create([
                'matchday_group_id' => $group->id,
                'player1_name' => $players[0]->first_name . ' ' . $players[0]->last_name,
                'player2_name' => $players[1]->first_name . ' ' . $players[1]->last_name,
                'winner_name' => $players[0]->first_name . ' ' . $players[0]->last_name
            ]);

            GroupMatch::create([
                'matchday_group_id' => $group->id,
                'player1_name' => $players[0]->first_name . ' ' . $players[0]->last_name,
                'player2_name' => $players[2]->first_name . ' ' . $players[2]->last_name,
                'winner_name' => $players[2]->first_name . ' ' . $players[2]->last_name
            ]);
        }

        // Crear brackets
        $bracket = Bracket::create(['matchday_id' => $matchday->id]);
        $round = BracketRound::create(['bracket_id' => $bracket->id, 'number' => 1]);

        BracketMatch::create([
            'bracket_round_id' => $round->id,
            'player1_name' => 'Jugador1 A',
            'player2_name' => 'Jugador2 B',
            'winner_name' => 'Jugador1 A'
        ]);
    }
}
