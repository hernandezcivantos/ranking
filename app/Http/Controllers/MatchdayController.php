<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Matchday;

class MatchdayController extends Controller
{
    public function index(Request $request, League $league)
    {
        $user = auth()->user();
        $group = $user->group;

        // Solo mostrar ligas del grupo del usuario
        $leagues = $group->leagues()->orderBy('name')->get();

        if ($leagues->isEmpty()) {
            return redirect()->route('dashboard')
                ->with('error', 'Este grupo no tiene ligas creadas aún.');
        }

        // Verificar que la liga pertenece al grupo
        if (!$group->leagues->contains($league->id)) {
            abort(403, 'No tienes acceso a esta liga');
        }

        $matchdays = $league->matchdays()->orderBy('created_at', 'desc')->get();

        return Inertia::render('Matchdays/Index', [
            'leagues' => $leagues,
            'currentLeagueId' => $league->id,
            'matchdays' => $matchdays,
        ]);
    }

    public function show(League $league, Matchday $matchday)
    {
        return Inertia::render('Matchdays/Show', [
            'league'   => $league,
            'matchday' => $matchday,
        ]);
    }

    public function play(Matchday $matchday)
    {
        return Inertia::render('Matchdays/PlayPanel', [
            'matchday' => $matchday->load('groups.players', 'groups.matches')
        ]);
    }

    public function generateRound(Request $request, Matchday $matchday)
    {
        $data = $request->validate([
            'type' => 'required|in:robin,brackets',
            'qualified_per_group' => 'required|integer|min:1',
            'sport' => 'required|in:tenis_mesa,futbol,custom',
            'sets' => 'nullable|integer',
            'duration' => 'nullable|integer',
        ]);

        if ($data['type'] === 'robin') {
            foreach ($matchday->groups as $group) {
                $players = $group->players;

                for ($i = 0; $i < count($players); $i++) {
                    for ($j = $i + 1; $j < count($players); $j++) {
                        $group->matches()->create([
                            'player1_name' => $players[$i]->first_name . ' ' . $players[$i]->last_name,
                            'player2_name' => $players[$j]->first_name . ' ' . $players[$j]->last_name,
                        ]);
                    }
                }
            }
        }

        // Más adelante: lógica para brackets

        return back()->with('success', 'Ronda tipo ' . $data['type'] . ' generada correctamente.');
    }

}
