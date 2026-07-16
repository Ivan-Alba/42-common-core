<?php

namespace App\Http\Controllers;

use App\Enums\FriendshipHttpAction;
use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use App\Models\User;
use App\Services\FriendshipService;
use App\Services\AchievementService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Events\FriendRequestReceived;
use App\Events\FriendRequestAccepted;

class FriendshipController 
{
    public function sendFriendRequest(User $user, User $friend, FriendshipService $service)
    {
        if ($user->id != Auth::id() || $user->id == $friend->id)
        {
            abort(403, __('errors.unauthorized'));
        }

        $friendship = $service->createFriendship($user, $friend);

		// Añadido Miriam
		FriendRequestReceived::dispatch($friend->id, $user);

        return response()->json([
            'user_id' => $friendship->user_id,
            'friend_id' => $friendship->friend_id,
            'requester_id' => $friendship->requester_id,
            'status' => $friendship->status,
            'chat_id' => $friendship->chat_id
        ], 201);
    }

    public function updateFriendship(User $user, User $friend, Request $request, FriendshipService $service)
    {
        $request->validate(['action' => ['required', Rule::enum(FriendshipHttpAction::class)]]);

        if ($user->id != Auth::id() || $user->id == $friend->id)
        {
            abort(403, __('errors.unauthorized'));
        }

		// If the action is reject, we delete the friendship instead of updating it to rejected, since it doesn't make sense to keep a rejected request in the database. This also simplifies the logic when sending a new request later, since we won't have to check for existing rejected requests.
		if ($request->action === 'reject' || $request->action === FriendshipHttpAction::REJECT->value) {
            
            $service->deleteFriendship($user, $friend);
            
            // Devolvemos un 200 OK vacío porque el registro ya no existe
            return response()->json(['message' => 'Solicitud rechazada y eliminada'], 200);
        }

        $friendship = $service->replyToFriendshipRequest($user, $friend, $request->action);

		// Añadido Miriam
		if ($friendship->status === 'accepted') {
			// Throw event passing the user who accepted ($user) and the ID of the original sender
            FriendRequestAccepted::dispatch($user, $friendship->requester_id);

            $achievementService = app(AchievementService::class);
            $achievementService->addProgress($user, 'SOCIAL_BUTTERFLY', 1);
            $achievementService->addProgress($friend, 'SOCIAL_BUTTERFLY', 1);
        }

        return response()->json([
            'user_id' => $friendship->user_id,
            'friend_id' => $friendship->friend_id,
            'requester_id' => $friendship->requester_id,
            'status' => $friendship->status,
            'chat_id' => $friendship->chat_id
        ], 200);
    }

    public function deleteFriendship(User $user, User $friend, FriendshipService $service)
    {
        if ($user->id != Auth::id() || $user->id == $friend->id)
        {
            abort(403, __('errors.unauthorized'));
        }

        $service->deleteFriendship($user, $friend);

        $achievementService = app(AchievementService::class);
        $achievementService->addProgress($user, 'SOCIAL_BUTTERFLY', -1);
        $achievementService->addProgress($friend, 'SOCIAL_BUTTERFLY', -1);
        FriendRequestAccepted::dispatch($user, $friend->id);

        return response()->json([], 204);
    }
}
