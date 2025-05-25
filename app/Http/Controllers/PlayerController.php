<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Player;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Division;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $group = auth()->user()->group;

        $search = $request->input('search');

        $players = Player::query()
            ->with(['division', 'club'])
            ->where('group_id', $group->id) // 👈 importante
            ->when($search, function ($query, $search) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            })
            ->orderBy('last_name')
            ->paginate(25)
            ->withQueryString();

        $divisions = Division::where('group_id', $group->id)->orderBy('name')->get();
        $clubs = Club::where('group_id', $group->id)->orderBy('name')->get();

        return Inertia::render('Players/Index', [
            'players' => $players,
            'divisions' => $divisions,
            'clubs' => $clubs,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        $group = auth()->user()->group;

        return Inertia::render('Players/Create', [
            'divisions' => Division::where('group_id', $group->id)->get(),
            'clubs' => Club::where('group_id', $group->id)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'paddle_type' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
            'club_id' => 'nullable|exists:clubs,id',
            'photo' => 'nullable|image|max:2048',
        ], [
            'first_name.required' => 'El campo nombre es obligatorio.',
            'last_name.required' => 'El campo apellidos es obligatorio.',
            'first_name.string' => 'El campo nombre debe ser un texto.',
            'last_name.string' => 'El campo apellidos debe ser un texto.',
            'first_name.max' => 'El campo nombre no puede tener más de 100 caracteres.',
            'last_name.max' => 'El campo apellidos no puede tener más de 100 caracteres.',
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.max' => 'La imagen no debe superar los 2MB.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('players', 'public');
        }

        Player::create($validated);
        return redirect()->route('players.index')->with('success', 'Jugador creado');
    }

    public function edit(Player $player)
    {

        $group = auth()->user()->group;

        return Inertia::render('Players/Create', [
            'player' => $player,
            'divisions' => Division::where('group_id', $group->id)->get(),
            'clubs' => Club::where('group_id', $group->id)->get(),
        ]);
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'paddle_type' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
            'club_id' => 'nullable|exists:clubs,id',
            'photo' => 'nullable|image|max:2048',
        ], [
            'first_name.required' => 'El campo nombre es obligatorio.',
            'last_name.required' => 'El campo apellidos es obligatorio.',
            'first_name.string' => 'El campo nombre debe ser un texto.',
            'last_name.string' => 'El campo apellidos debe ser un texto.',
            'first_name.max' => 'El campo nombre no puede tener más de 100 caracteres.',
            'last_name.max' => 'El campo apellidos no puede tener más de 100 caracteres.',
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.max' => 'La imagen no debe superar los 2MB.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('players', 'public');
        }

        $player->update($validated);
        return redirect()->route('players.index')->with('success', 'Jugador actualizado');
    }


    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Jugador eliminado');
    }
}
