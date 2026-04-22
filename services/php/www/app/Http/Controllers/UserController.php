<?php

namespace App\Http\Controllers;

use App\Enums\Language;
use App\Enums\OrderDirection;
use App\Enums\UserStatus;
use App\Http\Resources\FriendResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserMatch;
use App\Models\Achievement;
use App\Services\AchievementService;
use App\Http\Controllers\MatchmakingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController
{
    public function getUser(Request $request, User $user)
    {
        // Add PlayerStatsResource to UserResource
        $user->load('stats');

        // All achievements with translations + progress for THIS user
        $allAchievements = Achievement::with([
            'translations',
            'users' => function ($query) use ($user) {
                $query->where('users.id', $user->id);
            }
        ])->get();

        // Inject relation
        $user->setRelation('all_achievements_with_progress', $allAchievements);

        // Add match history
        $history = UserMatch::where('player_1_id', $user->id)
            ->orWhere('player_2_id', $user->id)
            ->with(['player1', 'player2'])
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $user->setRelation('match_history', $history);

        $userData = $user->toResource()->toArray($request);

        if (Auth::id() === $user->id) {
            $penaltyWaitTime = ($user->penalty_until && $user->penalty_until->isFuture())
                ? now()->diffInSeconds($user->penalty_until)
                : null;

            // Ahora sí, $userData es un array y puedes inyectar la clave
            $userData['penalty_remaining_seconds'] = $penaltyWaitTime;
        }

        return response()->json($userData, 200);
    }

    public function getUsers(Request $request)
    {
        $validated = $request->validate([
            'search' => ['sometimes', 'string', 'max:255'],
            'page_size' => ['sometimes', 'integer', 'min:1', 'max:' . config('social.max_page_size')],
            'page' => ['sometimes', 'integer', 'min:1'],
            'sort_order' => ['sometimes', Rule::enum(OrderDirection::class)],
        ]);

        $pageSize = $request->integer('page_size', config('social.default_page_size'));
        $page = $request->integer('page', 1);
        $sortOrder = $request->enum('sort_order', OrderDirection::class, OrderDirection::DESC);
        $searchQuery = $request->string('search')->trim();

        $query = User::query()
            ->where('is_bot', false);

        if ($searchQuery->isNotEmpty()) {
            $query->where('name', 'LIKE', $searchQuery . '%');
        }

        $users = $query->orderBy('created_at', $sortOrder->value)
            ->paginate($pageSize, ['*'], 'page', $page);

        return $users->toResourceCollection();
    }

    /**
     * Forcefully set the user status to OFFLINE.
     * Used during emergency quits or tab closures.
     */
    public function forceOffline(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            $user->update(['status' => UserStatus::OFFLINE]);

            $user->tokens()->where('name', 'unity_token')->delete();

            app(MatchmakingController::class)->handleUserDisconnection($user);

            Log::info("User ID {$user->id} forced OFFLINE via beacon.");
        } else {
            Log::warning("Force offline called but no authenticated user found.");
        }

        return response()->json([
            'success' => true,
            'message' => 'User status updated to OFFLINE'
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->noContent();
    }

    public function getOwnUser(Request $request)
    {
        $user = auth()->user();

        // Add PlayerStatsResource to UserResource
        $user->load('stats');

        // Add match history
        $history = UserMatch::where('player_1_id', $user->id)
            ->orWhere('player_2_id', $user->id)
            ->with(['player1', 'player2'])
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $user->setRelation('match_history', $history);

        // Unity token generation for authenticated API access
        $user->tokens()->where('name', 'unity_token')->delete();
        $token = $user->createToken('unity_token')->plainTextToken;

        return response()->json($user->toResource(), 200)
            ->header('X-Unity-Token', $token)
            ->header('Access-Control-Expose-Headers', 'X-Unity-Token');
    }

    public function updateOwnPassword(Request $request)
    {
        $request->validate(['password' => ['required', 'string', Password::default()]]);

        auth()->user()->forceFill([
            'password' => Hash::make($request['password']),
        ])->save();

        return response()->json(auth()->user()->toResource(), 200);
    }

    public function updateUser(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'bio' => [
                'nullable',
                'string',
                'max:255',
            ],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'language' => ['nullable', Rule::enum(Language::class)]
        ]);

        $user->fill([
            'name' => $request['username'] ?? $user->name,
            'email' => $request['email'] ?? $user->email,
            'bio' => $request['bio'] ?? $user->bio,
            'language' => $request['language'] ?? $user->language,
        ]);

        if ($request->hasFile('avatar')) {
            $user->updateAvatar($request['avatar']);
        }

        $user->save();

        return response()->json(auth()->user()->toResource(), 200);
    }

    public function claimAchievementReward(Request $request, int $achievementId)
    {
        /** @var User $user */
        $user = $request->user();

        // Inyectamos el servicio (o lo pides en el constructor)
        $achievementService = app(AchievementService::class);

        $result = $achievementService->claimReward($user, $achievementId);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 400);
        }

        return response()->json([
            'message' => $result['message'],
            'points_awarded' => $result['points']
        ], 200);
    }

    public function getFriends(User $user, Request $request)
    {
        if ($user->id != auth()->user()->id) {
            abort(403);
        }

        $pageSize = $request->integer('page_size', config('social.default_page_size'));
        $page = $request->integer('page', 1);
        $sortOrder = $request->enum('sort_order', OrderDirection::class, OrderDirection::DESC);
        $name = $request->string('name') ?? "";

        $friendships = \App\Models\Friendship::where('user_id', $user->id)
            ->orWhere('friend_id', $user->id)
            ->get();

        $friendIds = $friendships->map(function ($friendship) use ($user) {
            return $friendship->user_id === $user->id ? $friendship->friend_id : $friendship->user_id;
        });

        $friendsQuery = User::whereIn('id', $friendIds)
            ->orderBy('created_at', $sortOrder->value);

        if (!empty($name)) {
            $friendsQuery->where('name', 'like', '%' . $name . '%');
        }

        $friends = $friendsQuery->paginate($pageSize, ['*'], 'page', $page);

        $friends->getCollection()->transform(function ($friend) use ($friendships, $user) {
            $pivot = $friendships->first(function ($f) use ($friend, $user) {
                return ($f->user_id === $user->id && $f->friend_id === $friend->id) ||
                    ($f->user_id === $friend->id && $f->friend_id === $user->id);
            });

            $friend->pivot = $pivot;
            return $friend;
        });

        return FriendResource::collection($friends);
    }

    // Get UserResource + PlayerStatsResource to show ranking
    public function getRanking()
    {
        $topPlayers = User::join('player_stats', 'users.id', '=', 'player_stats.user_id')
            ->where('users.is_bot', false)
            ->orderBy('player_stats.ranked_points', 'desc')
            ->select('users.*')
            ->with('stats')
            ->take(100)
            ->get();

        return UserResource::collection($topPlayers);
    }
}
