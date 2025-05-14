<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use App\Models\MatchdayGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchdayGroupController extends Controller
{
    public function index(Matchday $matchday)
    {
        $groups = $matchday->groups()->withCount('players')->get();

        return Inertia::render('Matchdays/GroupsIndex', [
            'matchday' => $matchday,
            'groups' => $groups,
        ]);
    }

    public function create(Matchday $matchday)
    {
        $players = $matchday->league->players()->orderBy('last_name')->get();

        return Inertia::render('Matchdays/Create', [
            'matchday' => $matchday,
            'players' => $players,
        ]);
    }

    public function store(Request $request, Matchday $matchday)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'player_ids' => 'required|array',
            'player_ids.*' => 'exists:players,id'
        ]);

        $group = MatchdayGroup::create([
            'matchday_id' => $matchday->id,
            'name' => $data['name']
        ]);

        $group->players()->attach($data['player_ids']);

        return redirect()->route('matchdays.show', $matchday->id)
            ->with('success', 'Grupo creado correctamente.');
    }

    public function edit(Matchday $matchday, MatchdayGroup $group)
    {
        $this->authorize('update', $group); // Opcional: si usas políticas

        $players = $matchday->league->players()->get();
        $assigned = $group->players()->pluck('players.id')->toArray();

        return Inertia::render('Matchdays/EditGroup', [
            'group' => $group,
            'players' => $players,
            'assignedPlayerIds' => $assigned,
        ]);
    }

    public function update(Request $request, Matchday $matchday, MatchdayGroup $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'player_ids' => 'array',
            'player_ids.*' => 'exists:players,id',
        ]);

        $group->update(['name' => $request->name]);
        $group->players()->sync($request->player_ids);

        return redirect()->route('matchdays.show', $matchday->id)->with('success', 'Grupo actualizado correctamente.');
    }

    public function show(Matchday $matchday, MatchdayGroup $group)
    {
        return Inertia::render('Matchdays/ShowGroup', [
            'group' => $group,
            'matchday' => $matchday,
            'leagueId' => $matchday->league_id, // ✅ Añade esto
            'players' => $group->players()->with('division')->withPivot('points', 'position')->get(),
        ]);
    }
}
