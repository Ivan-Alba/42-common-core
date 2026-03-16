<?php
use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Route;

// TODO DEBUG ROUTE WITHOUT MIDDLEWARE
Route::get('/matches/{matchId}', [MatchController::class, 'getMatchData']);


Route::middleware('auth:sanctum')->group(function () {
 
});
