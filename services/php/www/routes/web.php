<?php

use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {return $request->user();});

    /**
     * Friend system
     * Chat system (Friend and ingame)
     * User profiles
     * Profile updates
     * 
     * Games
     * Tournaments
     * Game statistics
     * Match history
     * User game stadistics
     * Achievements
     * Progression
     * Leaderboards
     * Badges
     * 
     */
    Route::prefix('/v1')->group(function () {
        // Own user
        Route::get('/user', [UserController::class, 'getOwnUser']);
        Route::patch('/user/update', [UserController::class, 'updateUser']);
        Route::put('/user/password/update', [UserController::class, 'updateOwnPassword']);
    
        // User
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::get('/users/{user}', [UserController::class, 'getUser']);
        Route::get('/users/{user}/friends', [UserController::class, 'getFriends']);

        // PlayerStats
        Route::get('/user/{user}/stats', [PlayerStatsController::class, 'getUserStats']);

        // Cards
        Route::get('/user/cards', [CardUserController::class, 'getMyCards']);

        // Ranking
        Route::get('/ranking', [UserController::class, 'getRanking']);

        // Route::get('/users/{id}/games', [UserController::class, 'getUser']);
        // Route::get('/users/{id}/chats', [UserController::class, 'getUser']);
        // Route::get('/users/{id}/achievements', [UserController::class, 'getUser']);
        // Route::patch('/users/{id}', [UserController::class, 'getUser']);
        // Route::delete('/users/{id}', [UserController::class, 'getUser']);

        // Friendship
        Route::post('/users/{user}/friends/{friend}', [FriendshipController::class, 'sendFriendRequest']);
        Route::patch('/users/{user}/friends/{friend}', [FriendshipController::class, 'updateFriendship']);
        Route::delete('/users/{user}/friends/{friend}', [FriendshipController::class, 'deleteFriendship']);

        // Chat
        // Route::get('/chats/{chat}', [ChatController::class, 'getChat']); // Guests might be able to read chats, make it public.
        Route::post('/chats/{chat}/messages', [ChatController::class, 'postMessage']);
        Route::patch('/messages/{message}', [ChatController::class, 'editMessage']);
        Route::post('/messages/{message}/read', [ChatController::class, 'readMessage']);

        // Games
        // Route::get('/games', [UserController::class, 'getUser']);
        // Route::get('/games/{id}', [UserController::class, 'getUser']);
        Route::post('/games', [GameController::class, 'createGame']);

        // {
        // "match_id": "string",
        // "server_timestamp": 1705932000,
        // "config": {
        //     "board_size": 3,
        //     "hand_size": 5,
        //     "turn_time_limit": 30,
        //     "max_deck_cost": 999,
        //     "rules": ["open", "same", "plus"],
        //     "random_first_player": false,
        //     "first_player_id": "string"
        // },
        // "local_player": {
        //     "id": "string", "name": "string", "avatar_url": "url",
        //     "collection_ids": ["id1", "id2", "..."]
        // },
        // "opponent": {
        //     "id": "string", "name": "string", "avatar_url": "url",
        //     "is_ai": bool, "collection_ids": ["id10", "..."]
        // }
        // }
        Route::get('/games/{game}', [GameController::class, 'getGame']);

        // REQ: { "player_id": string, "card_ids": string[] }
        // RESP: { "success": bool, "data": { "confirmed": true } }
        Route::post('/games/{game}/confirm-deck', [GameController::class, 'confirmDeck']);
        
        // REQ: { "player_id": string, "card_id": string, "board_index": int }
        // RESP: 
        // {
        // "player_id": "player_local",
        // "card_id": "card_005",
        // "board_index": 4,
        // "animation_steps": [
        // {
        // "rule": "plus",
        // "card_indices": [3, 5],
        // "caused_by_index": 4
        // },
        // {
        // "rule": "combo",
        // "card_indices": [2],
        // "caused_by_index": 3
        // },
        // {
        // "rule": "normal",
        // "card_indices": [1],
        // "caused_by_index": 4
        // }
        // ],
        // "match_over": false
        // }
        Route::post('/games/{game}/play-card', [GameController::class, 'playCard']);


        // Achivements
        // Route::get('/achievements', [UserController::class, 'getUsers']);
        // Route::get('/achievements/{id}/users', [UserController::class, 'getUsers']);
        // Route::put('/achievements/{id}/users/{user_id}', [UserController::class, 'getUsers']);

        // Tournaments
        // Route::get('/tournaments', [UserController::class, 'getUsers']);
        // Route::get('/tournaments/{id}', [UserController::class, 'getUsers']);
        // Route::post('/tournaments', [UserController::class, 'getUsers']);
        // Route::delete('/tournaments/{id}', [UserController::class, 'getUsers']);
        // Route::patch('/tournaments/{id}', [UserController::class, 'getUsers']);

        // Other
        // Route::get('/leaderboard', [UserController::class, 'getUsers']);
    });

});

Route::get('/v1/chats/{chat}', [ChatController::class, 'getChat']);

Route::get(config('oauth.uri_generation'), [OAuthController::class, 'getRedirectUri']);

Route::post(config('oauth.redirected').'/{provider}', [OAuthController::class, 'handleOAuthResponse']);

Route::get('/media/{path}', [MediaController::class, 'getMedia'])->where('path', '.*');
