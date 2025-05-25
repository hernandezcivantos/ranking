<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    DashboardController, GroupController, LeagueController,
    MatchdayController, MatchdayGroupController, PlayerController, ClubController,
    PublicGroupController, PublicLeagueController, Public\JornadaPublicController
};

Route::get('/', fn() => Inertia::render('Landing'))->name('landing');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Jornadas
    Route::post('/matchdays/{matchday}/generate-round', [MatchdayController::class, 'generateRound'])->name('matchdays.generateRound');
    Route::post('/matchdays/{matchday}/recalculate-round', [MatchdayController::class, 'recalculateRound'])->name('matchdays.recalculateRound');
    Route::patch('/group-matches/{match}/finish', [MatchdayController::class, 'finishMatch'])->name('group-matches.finish');

    Route::get('/matchdays', function () {
        $group = auth()->user()->group;
        $league = $group->leagues()->first();
        return $league
            ? redirect()->route('leagues.matchdays.index', ['league' => $league->id])
            : redirect()->route('dashboard')->with('error', 'No tienes ligas disponibles');
    });

    Route::resource('groups', GroupController::class);

    Route::prefix('groups/{group}')->name('group.')->group(function () {
        Route::resource('leagues', LeagueController::class);
        Route::post('leagues/{league}', [LeagueController::class, 'update'])->name('leagues.update');
    });

    Route::prefix('leagues/{league}')->name('leagues.')->group(function () {
        Route::get('matchdays', [MatchdayController::class, 'index'])->name('matchdays.index');
        Route::get('matchdays/create', [MatchdayController::class, 'create'])->name('matchdays.create');
        Route::post('matchdays', [MatchdayController::class, 'store'])->name('matchdays.store');
        Route::get('matchdays/{matchday}/edit', [MatchdayController::class, 'edit'])->name('matchdays.edit');
        Route::get('matchdays/{matchday}', [MatchdayController::class, 'show'])->name('matchdays.show');
        Route::post('matchdays/{matchday}', [MatchdayController::class, 'update'])->name('matchdays.update');
    });

    Route::get('/leagues/{league}/ranking', [LeagueController::class, 'ranking'])->name('leagues.ranking');
    Route::get('/matchdays/{matchday}/play', [MatchdayController::class, 'play'])->name('matchdays.play');
    Route::get('/matchdays/{matchday}/finalize', [MatchdayController::class, 'editFinal'])->name('matchdays.final.edit');
    Route::post('/matchdays/{matchday}/finalize', [MatchdayController::class, 'finalize'])->name('matchdays.finalize');

    Route::prefix('matchdays/{matchday}/groups')->name('matchdays.groups.')->group(function () {
        Route::get('/', [MatchdayGroupController::class, 'index'])->name('index');
        Route::get('create', [MatchdayGroupController::class, 'create'])->name('create');
        Route::post('/', [MatchdayGroupController::class, 'store'])->name('store');
        Route::get('{group}', [MatchdayGroupController::class, 'show'])->name('show');
        Route::get('{group}/edit', [MatchdayGroupController::class, 'edit'])->name('edit');
        Route::put('{group}', [MatchdayGroupController::class, 'update'])->name('update');
    });

    Route::post('/players/{player}', [PlayerController::class, 'update'])->name('players.update');
    Route::resource('players', PlayerController::class);

    Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
    Route::put('/clubs/{club}', [ClubController::class, 'update'])->name('clubs.update');
});

Route::get('/public/{group:slug}', [PublicGroupController::class, 'show'])->name('public.group');
Route::get('/public/{group:slug}/{league:slug}', [PublicLeagueController::class, 'ranking'])->name('public.league');
Route::get('/public/matchday/{matchday}', fn($matchday) => Inertia::render('PublicJornadaView', ['jornadaId' => (int) $matchday]))->name('public.matchday');
Route::get('/public/matchday/{jornadaId}/json', [JornadaPublicController::class, 'show']);

require __DIR__.'/auth.php';
