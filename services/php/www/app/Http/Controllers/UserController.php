<?php

namespace App\Http\Controllers;

use App\Enums\Language;
use App\Enums\OrderDirection;
use App\Http\Resources\FriendResource;
use App\Http\Resources\UserCollection;
use App\Models\OAuthExchange;
use App\Models\User;
use App\OAuth\Contracts\OAuthServer;
use App\OAuth\Factories\OAuthServerFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController
{
	public function getUser(Request $request, User $user)
	{
        // Add PlayerStatsResource to UserResource
        $user->load('stats');

        // Add match history
        $history = UserMatch::where('player_1_id', $user->id)
        ->orWhere('player_2_id', $user->id)
        ->with(['player1', 'player2']) 
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

        $user->setRelation('match_history', $history);

		return response()->json($user->toResource(), 200);
	}

	public function getUsers(Request $request)
	{
		$validated = $request->validate([
			'page_size' => ['sometimes', 'integer', 'min:1', 'max:' . config('social.max_page_size')],
			'page' => ['sometimes', 'integer', 'min:1'],
			'sort_order' => ['sometimes', Rule::enum(OrderDirection::class)],
		]);

		$pageSize = $request->integer('page_size', config('social.default_page_size'));
		$page = $request->integer('page', 1);
		$sortOrder =  $request->enum('sort_order', OrderDirection::class, OrderDirection::DESC);

		$users = User::orderBy('created_at', $sortOrder->value)->paginate($pageSize, ['*'], 'page', $page);

		return $users->toResourceCollection();
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
        ->take(10)
        ->get();

        $user->setRelation('match_history', $history);

        return response()->json($user->toResource(), 200);
    }

	public function updateOwnPassword(Request $request)
	{
		$request->validate(['password' =>  ['required', 'string', Password::default()]]);

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

	// public function getFriends(User $user, Request $request)
	// {
	// 	if ($user->id != auth()->user()->id) {
	// 		abort(403);
	// 	}

	// 	$validated = $request->validate([
	// 		'page_size' => ['sometimes', 'integer', 'min:1', 'max:' . config('social.max_page_size')],
	// 		'page' => ['sometimes', 'integer', 'min:1'],
	// 		'sort_order' => ['sometimes', Rule::enum(OrderDirection::class)],
	// 		'name' => ['sometimes', 'string', 'max:255'],
	// 	]);

	// 	$pageSize = $request->integer('page_size', config('social.default_page_size'));
	// 	$page = $request->integer('page', 1);
	// 	$sortOrder =  $request->enum('sort_order', OrderDirection::class, OrderDirection::DESC);
	// 	$name = $request->string('name') ?? "";

	// 	$friends = $user->friendsOfMine()
	// 		->orderBy('created_at', $sortOrder->value)
	// 		->when(!empty($name), fn($query) => $query->where('name', 'like', '%' . $name . '%'))
	// 		->paginate($pageSize, ['*'], 'page', $page);

	// 	return FriendResource::collection($friends);
	// }

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
}
