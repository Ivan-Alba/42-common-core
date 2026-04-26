<?php

use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PlayerStatsController;
use App\Http\Controllers\CardUserController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\MatchmakingController;
use App\Http\Controllers\ActiveMatchController;
use App\Http\Middleware\UpdateUserActivity;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| Authenticated Web Routes (With Activity Tracking)
|--------------------------------------------------------------------------
| These routes require Sanctum authentication and trigger the
| UpdateUserActivity middleware to keep the user ONLINE.
*/
Route::middleware(['auth:sanctum', UpdateUserActivity::class])->group(function () {


    /* Basic user check */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('/v1')->group(function () {

        /* Own user management */
        Route::get('/user', [UserController::class, 'getOwnUser']);
        Route::put('/user/password/update', [UserController::class, 'updateOwnPassword']);

        /* User directory & Friends */
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::get('/users/{user}', [UserController::class, 'getUser']);

        /* Player statistics */
        Route::get('/user/{user}/stats', [PlayerStatsController::class, 'getUserStats']);

        /* Card collection */
        Route::get('/cards', [CardController::class, 'index']);
        Route::get('/user/cards', [CardUserController::class, 'getMyCards']);

        /* Leaderboard and Ranking */
        Route::get('/ranking', [UserController::class, 'getRanking']);

        /* Matchmaking system */
        Route::post('/matchmaking/cancel', [MatchmakingController::class, 'cancel']);

        /* Active matches */
        Route::post('/match/{matchUuid}/abandon', [ActiveMatchController::class, 'abandon']);

        /* Friendship actions */
        Route::post('/users/{user}/friends/{friend}', [FriendshipController::class, 'sendFriendRequest']);
        Route::patch('/users/{user}/friends/{friend}', [FriendshipController::class, 'updateFriendship']);
        Route::delete('/users/{user}/friends/{friend}', [FriendshipController::class, 'deleteFriendship']);

        /* Chat and messaging */
        Route::post('/chats/{chat}/messages', [ChatController::class, 'postMessage']);
        Route::patch('/messages/{message}', [ChatController::class, 'editMessage']);
        Route::post('/messages/{message}/read', [ChatController::class, 'readMessage']);

        /* Game session management (Web interface) */
        Route::post('/games', [GameController::class, 'createGame']);
        Route::get('/games/{game}', [GameController::class, 'getGame']);
        Route::post('/games/{game}/confirm-deck', [GameController::class, 'confirmDeck']);
        Route::post('/games/{game}/play-card', [GameController::class, 'playCard']);
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Web Routes (WITHOUT Activity Tracking)
|--------------------------------------------------------------------------
| Excluded to prevent accidental queue removal or state conflicts.
*/
Route::middleware(['auth:sanctum'])->prefix('/v1')->group(function () {

    Route::post('/user/force-offline', [UserController::class, 'forceOffline']);

    Route::patch('/user/update', [UserController::class, 'updateUser']);

    Route::get('/users/{user}/friends', [UserController::class, 'getFriends']);

    /* Matchmaking */
    Route::post('/matchmaking/join', [MatchmakingController::class, 'join']);
    Route::post('/matchmaking/confirm/{uuid}', [MatchmakingController::class, 'confirm']);
});


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
/* Public chat viewing */
Route::get('/v1/chats/{chat}', [ChatController::class, 'getChat']);

/* OAuth Authentication routes */
Route::get(config('oauth.uri_generation'), [OAuthController::class, 'getRedirectUri']);
Route::post(config('oauth.redirected') . '/{provider}', [OAuthController::class, 'handleOAuthResponse']);

/* Media and asset delivery */
Route::get('/media/{path}', [MediaController::class, 'getMedia'])->where('path', '.*');

/* Default fallback for non-existing routes */
Route::fallback(fn() => redirect('/error'));

