<?php

use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TorneosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\PlayerController as ApiPlayerController;
use App\Http\Controllers\PlayerController as PlayerController;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return Inertia::render('Ranking');
})->name('ranking');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // PLayers
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');
    Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
    Route::get('/players/{player}', [PlayerController::class, 'show'])->name('players.show');
    Route::get('/players/{player}/edit', [PlayerController::class, 'edit'])->name('players.edit');
    Route::match(['PUT', 'POST'], '/players/{player}', [PlayerController::class, 'update'])->name('players.update');
    Route::delete('/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');
    // Torneos
    Route::get('/torneos', [TorneosController::class, 'index'])->name('torneos.index');
    Route::resource('torneos', TorneosController::class)->except(['destroy']);
    Route::patch('torneos/{torneo}/estado', [TorneosController::class, 'cambiarEstado'])->name('torneos.estado');
    Route::get('/torneos/{torneo}/gestionar', [TorneosController::class, 'gestionar'])->name('torneos.gestionar');
    // Grupos
    Route::post('/grupos', [GrupoController::class, 'store']);
    Route::post('/grupos/{grupo}/asignar-jugadores', [GrupoController::class, 'asignarJugadores']);
    Route::get('/grupos/{grupo}/jugadores', [GrupoController::class, 'jugadores']);
});

// API

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/players', [ApiPlayerController::class, 'index']);
});
require __DIR__.'/auth.php';
