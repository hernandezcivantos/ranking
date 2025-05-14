<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Group;
use App\Models\League;
use App\Models\Matchday;
use App\Models\MatchdayGroup;
use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ejecutar el seeder de divisiones primero
        $this->call([
            DivisionSeeder::class,
        ]);

        // Crear usuario admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'hernandez.civantos@gmail.com',
            'password' => bcrypt('123'),
            'is_admin' => true,
        ]);

        // Crear grupo
        $group = Group::create([
            'user_id' => $admin->id,
            'name' => 'Vegas del Genil',
            'description' => 'CTM Vegas del genil',
        ]);

        // Obtener divisiones para asignar aleatoriamente
        $divisions = Division::all();

        // Crear jugadores
        $players = collect();
        foreach (range(1, 12) as $i) {
            $players->push(Player::create([
                'first_name' => 'Player',
                'last_name' => "#{$i}",
                'division_id' => $divisions->random()->id,
                'paddle_type' => 'Offensive',
                'rubber_style' => 'Smooth',
                'matches_played' => rand(0, 10),
                'matches_won' => rand(0, 10),
                'matches_lost' => rand(0, 10),
            ]));
        }

        // Crear liga
        $league = League::create([
            'group_id' => $group->id,
            'name' => 'Liga Interna',
            'description' => 'Liga Interna de Vegas del genil',
        ]);

        // Asociar jugadores a la liga
        foreach ($players as $player) {
            $league->players()->attach($player->id, ['points' => rand(0, 100)]);
        }

        // Crear jornada
        $matchday = Matchday::create([
            'league_id' => $league->id,
            'name' => 'Matchday 1',
            'date' => now(),
        ]);

        // Crear dos grupos en la jornada
        $groupA = MatchdayGroup::create(['matchday_id' => $matchday->id, 'name' => 'Group A']);
        $groupB = MatchdayGroup::create(['matchday_id' => $matchday->id, 'name' => 'Group B']);

        // Dividir jugadores en 2 grupos
        $half = ceil($players->count() / 2);
        $players->slice(0, $half)->each(function ($player, $i) use ($groupA) {
            $groupA->players()->attach($player->id, [
                'position' => $i + 1,
                'points' => max(5 - $i, 0),
            ]);
        });
        $players->slice($half)->values()->each(function ($player, $i) use ($groupB) {
            $groupB->players()->attach($player->id, [
                'position' => $i + 1,
                'points' => max(5 - $i, 0),
            ]);
        });
    }
}
