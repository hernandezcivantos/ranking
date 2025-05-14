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
}
