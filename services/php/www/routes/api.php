<?php
use App\Http\Controllers\ActiveMatchController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/matches/{matchUuid}', [ActiveMatchController::class, 'getMatchData']);
    Route::get('/cards', [CardController::class, 'index']);
    Route::post('/matches/{matchUuid}/update-selection', [ActiveMatchController::class, 'updateSelection']);
    Route::post('/matches/{matchUuid}/confirm-deck', [ActiveMatchController::class, 'confirmDeck']);
    Route::post('/matches/{matchUuid}/play-card', [ActiveMatchController::class, 'playCard']);
});
