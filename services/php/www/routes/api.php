<?php

use App\Http\Controllers\ActiveMatchController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\NetworkController;
use App\Http\Middleware\SetUserPlaying;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Game API Routes (Unity / In-Game)
|--------------------------------------------------------------------------
| These routes are called by the game engine. We use SetUserPlaying
| to ensure the user's status is always PLAYING and to update last_activity.
*/
Route::middleware(['auth:sanctum', SetUserPlaying::class])->group(function () {

    /* Network & Connectivity checks */
    Route::post('/network/ping', [NetworkController::class, 'sendPong']);

    /* Match and Card Data fetching */
    Route::get('/matches/{matchUuid}', [ActiveMatchController::class, 'getMatchData']);
    Route::get('/cards', [CardController::class, 'index']);

    /* In-game Actions */
    Route::post('/matches/{matchUuid}/update-selection', [ActiveMatchController::class, 'updateSelection']);
    Route::post('/matches/{matchUuid}/confirm-deck', [ActiveMatchController::class, 'confirmDeck']);
    Route::post('/matches/{matchUuid}/play-card', [ActiveMatchController::class, 'playCard']);

});
