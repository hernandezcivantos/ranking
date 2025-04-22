<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Player;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'nombre' => 'required|string|max:255'
        ]);

        $grupo = Grupo::create([
            'torneo_id' => $request->torneo_id,
            'nombre' => $request->nombre
        ]);

        return response()->json($grupo, 201);
    }

    public function asignarJugadores(Request $request, Grupo $grupo)
    {
        $request->validate([
            'jugadores' => 'required|array',
            'jugadores.*' => 'exists:players,id'
        ]);

        $grupo->jugadores()->sync($request->jugadores);

        return response()->json(['message' => 'Jugadores asignados correctamente']);
    }

    public function jugadores(Grupo $grupo)
    {
        return $grupo->jugadores;
    }
}
