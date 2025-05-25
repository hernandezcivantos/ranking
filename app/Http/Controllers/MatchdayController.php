<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Matchday;
use App\Models\GroupMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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

    public function play(Matchday $matchday)
    {
        $matchday->load([
            'groups.players.club',
            'groups.matches.player1',
            'groups.matches.player2',
        ]);

        return Inertia::render('Matchdays/PlayPanel', [
            'matchday' => $matchday,
        ]);
    }

    public function generateRound(Request $request, Matchday $matchday)
    {
        $data = $request->validate([
            'round_type' => 'required|string',
            'qualified_per_group' => 'required|integer',
            'sport' => 'required|string',
            'sets_per_match' => 'required|integer',
            'fields_total' => 'required|integer',
        ]);

        $matchday->update([
            'status' => 'en_marcha',
            'round_type' => $data['round_type'],
            'qualified_per_group' => $data['qualified_per_group'],
            'sport' => $data['sport'],
            'sets_per_match' => $data['sets_per_match'],
            'fields_total' => $data['fields_total'],
        ]);

        DB::transaction(function () use ($matchday, $data) {
            $matchday->load('groups.players');

            // ⚠️ Eliminar solo los partidos no finalizados
            GroupMatch::whereHas('group', function ($q) use ($matchday) {
                $q->where('matchday_id', $matchday->id);
            })->where('is_finished', false)->delete();

            $fieldLimit = $data['fields_total'];
            $fieldUsage = [];

            foreach ($matchday->groups as $group) {
                $players = $group->players;
                $matches = [];

                for ($i = 0; $i < count($players); $i++) {
                    for ($j = $i + 1; $j < count($players); $j++) {
                        $matches[] = [$players[$i], $players[$j]];
                    }
                }

                foreach ($matches as $pair) {
                    // ❗ Comprobar si este par ya jugó (y fue finalizado)
                    $alreadyPlayed = GroupMatch::where('group_id', $group->id)
                        ->where(function ($q) use ($pair) {
                            $q->where('player1_id', $pair[0]->id)->where('player2_id', $pair[1]->id)
                                ->orWhere(function ($q2) use ($pair) {
                                    $q2->where('player1_id', $pair[1]->id)->where('player2_id', $pair[0]->id);
                                });
                        })
                        ->where('is_finished', true)
                        ->exists();

                    if ($alreadyPlayed) continue;

                    // Asignar campo
                    $assignedField = null;
                    if (count($fieldUsage) < $fieldLimit) {
                        for ($f = 1; $f <= $fieldLimit; $f++) {
                            if (!in_array($f, $fieldUsage)) {
                                $assignedField = $f;
                                $fieldUsage[] = $f;
                                break;
                            }
                        }
                    }

                    GroupMatch::create([
                        'group_id' => $group->id,
                        'player1_id' => $pair[0]->id,
                        'player2_id' => $pair[1]->id,
                        'status' => 'pending',
                        'field_number' => $assignedField,
                        'is_finished' => false,
                        'sets' => [],
                    ]);
                }
            }
        });

        return back()->with('success', 'Partidos generados correctamente.');
    }

    public function recalculateRound(Request $request, Matchday $matchday)
    {
        DB::transaction(function () use ($matchday) {
            $matchday->load('groups.players');
            $fieldLimit = $matchday->fields_total ?? 1;

            $inUse = GroupMatch::whereHas('group', function ($q) use ($matchday) {
                $q->where('matchday_id', $matchday->id);
            })->whereNotNull('field_number')
                ->where('is_finished', false)
                ->pluck('field_number')
                ->toArray();

            $fieldUsage = array_filter($inUse);

            foreach ($matchday->groups as $group) {
                $players = $group->players;

                for ($i = 0; $i < count($players); $i++) {
                    for ($j = $i + 1; $j < count($players); $j++) {
                        $player1 = $players[$i];
                        $player2 = $players[$j];

                        $exists = GroupMatch::where('group_id', $group->id)
                            ->where(function ($q) use ($player1, $player2) {
                                $q->where('player1_id', $player1->id)
                                    ->where('player2_id', $player2->id)
                                    ->orWhere(function ($q2) use ($player1, $player2) {
                                        $q2->where('player1_id', $player2->id)
                                            ->where('player2_id', $player1->id);
                                    });
                            })->exists();

                        if (!$exists) {
                            $assignedField = null;

                            if (count($fieldUsage) < $fieldLimit) {
                                for ($f = 1; $f <= $fieldLimit; $f++) {
                                    if (!in_array($f, $fieldUsage)) {
                                        $assignedField = $f;
                                        $fieldUsage[] = $f;
                                        break;
                                    }
                                }
                            }

                            GroupMatch::create([
                                'group_id' => $group->id,
                                'player1_id' => $player1->id,
                                'player2_id' => $player2->id,
                                'status' => 'pending',
                                'field_number' => $assignedField,
                                'is_finished' => false,
                                'sets' => [],
                            ]);
                        }
                    }
                }
            }
        });

        return back()->with('success', 'Encuentros recalculados sin duplicados.');
    }

    public function finishMatch(Request $request, GroupMatch $match)
    {
        $data = $request->validate([
            'sets' => 'required|array',
        ]);

        DB::transaction(function () use ($match, $data) {
            $match->sets = $data['sets'];
            $match->is_finished = true;
            $match->status = 'finished';
            $freedField = $match->field_number;
            $match->field_number = null;
            $match->save();

            if ($freedField) {
                $next = GroupMatch::where('group_id', $match->group_id)
                    ->where('is_finished', false)
                    ->whereNull('field_number')
                    ->orderBy('id')
                    ->first();

                if ($next) {
                    $next->field_number = $freedField;
                    $next->save();
                }
            }
        });

        return back(303);
    }
}
