<?php
use App\Http\Controllers\ActiveMatchController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/matches/{matchId}', [ActiveMatchController::class, 'getMatchData']);
    Route::get('/cards', [CardController::class, 'index']);
});
