<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClubController extends Controller
{
    public function index()
    {
        $group = auth()->user()->group;

        if (!$group) {
            abort(403, 'No tienes un grupo asignado.');
        }

        $clubs = Club::where('group_id', $group->id)
            ->orderBy('name')
            ->get();

        return Inertia::render('Clubs/Index', [
            'clubs' => $clubs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $group = auth()->user()->group;

        if (!$group) {
            abort(403, 'No tienes un grupo asignado.');
        }

        Club::create([
            'group_id' => $group->id,
            'name' => $request->name,
        ]);

        return redirect()
            ->route('clubs.index')
            ->with('success', 'Club creado correctamente');
    }

    public function update(Request $request, Club $club)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $userGroupId = auth()->user()->group?->id;

        if ($club->group_id !== $userGroupId) {
            abort(403, 'No autorizado para modificar este club.');
        }

        $club->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('clubs.index')
            ->with('success', 'Club actualizado correctamente');
    }

    // Por ahora no implementamos destroy()
}
