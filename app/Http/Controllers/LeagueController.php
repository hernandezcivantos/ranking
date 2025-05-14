<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\League;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeagueController extends Controller
{
    public function index(Group $group)
    {
        $user = auth()->user();

        // Validar que el usuario pertenece a este grupo
        if (!$user->group || $user->group->id !== $group->id) {
            abort(403, 'No tienes acceso a este grupo');
        }

        $leagues = $group->leagues()->orderBy('name')->get();

        return Inertia::render('Leagues/Index', [
            'groupId' => $group->id,
            'groupSlug' => $group->slug,
            'leagues' => $leagues,
        ]);
    }

    public function create(Group $group)
    {
        return Inertia::render('Leagues/Form', [
            'group' => $group,
            'league' => null,
            'existingRules' => [],
        ]);
    }

    public function store(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:leagues,slug',
            'description' => 'nullable|string',
            'point_rules' => 'nullable|array',
            'point_rules.*.position' => 'required|integer|min:1',
            'point_rules.*.points' => 'required|integer|min:0',
        ]);

        $league = $group->leagues()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['point_rules'])) {
            foreach ($validated['point_rules'] as $rule) {
                $league->pointRules()->create($rule);
            }
        }

        return redirect()->route('group.leagues.index', $group->id)
            ->with('success', 'Liga creada correctamente.');
    }

    public function edit(Group $group, League $league)
    {
        return Inertia::render('Leagues/Form', [
            'group' => $group,
            'league' => $league,
            'existingRules' => $league->pointRules()->get(['position', 'points']),
        ]);
    }

    public function update(Request $request, Group $group, League $league)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:leagues,slug,' . $league->id,
            'description' => 'nullable|string',
            'point_rules' => 'nullable|array',
            'point_rules.*.position' => 'required|integer|min:1',
            'point_rules.*.points' => 'required|integer|min:0',
        ]);

        $league->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
        ]);

        $league->pointRules()->delete();

        if (!empty($validated['point_rules'])) {
            foreach ($validated['point_rules'] as $rule) {
                $league->pointRules()->create($rule);
            }
        }

        return redirect()->route('group.leagues.index', $group->id)
            ->with('success', 'Liga actualizada correctamente.');
    }

    public function ranking(League $league)
    {
        $players = $league->players()
            ->with('division')
            ->withPivot('points')
            ->orderByDesc('league_player.points')
            ->get();

        return Inertia::render('Leagues/Ranking', [
            'league' => $league,
            'players' => $players,
        ]);
    }
}
