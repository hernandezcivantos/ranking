<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Torneo;
use App\Models\Grupo;
use Illuminate\Http\Request;

class TorneosController extends Controller
{
    public function index()
    {
        $torneos = Torneo::with('grupos.jugadores')->orderByDesc('id')->get();
        return inertia('Torneos/Index', compact('torneos'));
    }

    public function create()
    {
        return inertia('Torneos/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $torneo = Torneo::create([
            'nombre' => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => 'pendiente',
        ]);

        $torneo->loadCount('grupos');

        return response()->json($torneo);
    }

    public function edit(Torneo $torneo)
    {
        $torneo->load('grupos.jugadores');
        return inertia('Torneos/Edit', compact('torneo'));
    }

    public function update(Request $request, Torneo $torneo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $torneo->update($request->only('nombre', 'fecha_inicio', 'fecha_fin'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Torneo actualizado');
    }

    public function cambiarEstado(Request $request, Torneo $torneo)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_marcha,parado,finalizado,cancelado'
        ]);

        $torneo->estado = $request->estado;
        $torneo->save();

        return back()->with('success', 'Estado actualizado');
    }

    public function gestionar(Torneo $torneo)
    {
        $torneo->load('grupos.jugadores');
        $players = Player::orderByDesc('first_name')->get();

        return inertia('Torneos/Gestionar', compact('torneo', 'players'));
    }
}

