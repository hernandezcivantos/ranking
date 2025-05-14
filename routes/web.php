<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\MatchdayGroupController;
use App\Http\Controllers\PublicGroupController;
use App\Http\Controllers\PublicLeagueController;
use App\Http\Controllers\PlayerController;

// 🌐 Landing pública (Vue)
Route::get('/', function () {
    return Inertia::render('Landing');
})->name('landing');

// 🔐 Rutas privadas (requieren login)
Route::middleware(['auth'])->group(function () {

    // Panel principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔁 Redirección desde /matchdays a la primera liga del grupo
    Route::get('/matchdays', function () {
        $group = auth()->user()->group;
        $league = $group->leagues()->first();

        if (!$league) {
            return redirect()->route('dashboard')->with('error', 'No tienes ligas disponibles');
        }

        return redirect()->route('leagues.matchdays.index', ['league' => $league->id]);
    });

    // Grupos del usuario
    Route::resource('groups', GroupController::class);

    // Ligas dentro de cada grupo
    Route::prefix('groups/{group}')->name('group.')->group(function () {
        Route::resource('leagues', LeagueController::class);
        Route::post('leagues/{league}', [LeagueController::class, 'update'])->name('leagues.update'); // Para editar con FormData
    });

    // Jornadas dentro de cada liga
    Route::prefix('leagues/{league}')->name('leagues.')->group(function () {
        Route::get('matchdays', [MatchdayController::class, 'index'])->name('matchdays.index');
        Route::get('matchdays/create', [MatchdayController::class, 'create'])->name('matchdays.create');
        Route::post('matchdays', [MatchdayController::class, 'store'])->name('matchdays.store');
        Route::get('matchdays/{matchday}/edit', [MatchdayController::class, 'edit'])->name('matchdays.edit');
        Route::get('matchdays/{matchday}', [MatchdayController::class, 'show'])->name('matchdays.show');
        Route::post('matchdays/{matchday}', [MatchdayController::class, 'update'])->name('matchdays.update');
    });

    // Ranking de liga
    Route::get('/leagues/{league}/ranking', [LeagueController::class, 'ranking'])->name('leagues.ranking');

    // Finalizar jornada
    Route::get('/matchdays/{matchday}/finalize', [MatchdayController::class, 'editFinal'])->name('matchdays.final.edit');
    Route::post('/matchdays/{matchday}/finalize', [MatchdayController::class, 'finalize'])->name('matchdays.finalize');

    // Grupos de jugadores por jornada
    Route::prefix('matchdays/{matchday}/groups')->name('matchdays.groups.')->group(function () {
        Route::get('/', [MatchdayGroupController::class, 'index'])->name('index');
        Route::get('create', [MatchdayGroupController::class, 'create'])->name('create');
        Route::post('/', [MatchdayGroupController::class, 'store'])->name('store');
        Route::get('{group}', [MatchdayGroupController::class, 'show'])->name('show');
        Route::get('{group}/edit', [MatchdayGroupController::class, 'edit'])->name('edit');
        Route::put('{group}', [MatchdayGroupController::class, 'update'])->name('update');
    });

    // Jugadores
    Route::post('/players/{player}', [PlayerController::class, 'update'])->name('players.update');
    Route::resource('players', PlayerController::class);
});

// 📣 Rutas públicas
Route::get('/public/{group:slug}', [PublicGroupController::class, 'show'])->name('public.group');
Route::get('/public/{group:slug}/{league:slug}', [PublicLeagueController::class, 'ranking'])->name('public.league');

// 🛡️ Autenticación
require __DIR__.'/auth.php';
