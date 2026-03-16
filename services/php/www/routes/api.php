<?php
use App\Http\Controllers\MatchController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

// TODO DEBUG ROUTE WITHOUT MIDDLEWARE
Route::get('/matches/{matchId}', [MatchController::class, 'getMatchData']);
Route::get('/cards', [CardController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
 
});
