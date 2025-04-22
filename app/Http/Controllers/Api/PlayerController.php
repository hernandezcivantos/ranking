<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        // Devolveremos todos los jugadores ordenados por partidos ganados
        return response()->json(
            Player::with('division')->orderByDesc('score')->get()
        );
    }
}
