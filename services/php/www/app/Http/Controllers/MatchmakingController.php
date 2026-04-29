<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Services\MatchmakingService;
use App\Enums\GameMode;
use App\Enums\MatchStatus;
use App\Events\MatchReady;
use App\Events\MatchCancelledEvent;
use App\Models\ActiveMatch;
use App\Models\User;
use App\Models\MatchmakingQueue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MatchmakingController extends Controller
{
    public function __construct(protected MatchmakingService $matchmaking)
    {
    }

    /**
     * Endpoint: POST /api/matchmaking/join
     * Request: { "game_mode": "CAMPAIGN_1" }
     */
    public function join(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode' => 'required|string'
        ]);

        try {
            $mode = GameMode::from($request->game_mode);
            $user = $request->user();

            if ($user->penalty_until && $user->penalty_until->isFuture()) {
                $secondsLeft = $user->penalty_until->diffInSeconds(now());

                return response()->json([
                    'success' => false,
                    'error' => 'You are temporarily banned from matchmaking.',
                    'seconds_remaining' => $secondsLeft,
                    'penalty_until' => $user->penalty_until->toIso8601String()
                ], 403);
            }

            $alreadyInMatch = ActiveMatch::where(function ($query) use ($user) {
                $query->where('player_1_id', $user->id)
                    ->orWhere('player_2_id', $user->id);
                })
                ->where('status', '!=', MatchStatus::FINISHED->value)
                ->exists();

            if ($alreadyInMatch) {
                return response()->json([
                    'success' => false,
                    'error' => 'You are already in an active match.'
                ], 409);
            }

            $match = $this->matchmaking->findOrCreateMatch($user, $mode);

            //$user->update(['status' => UserStatus::QUEUEING]);


            // If $match is null, it means the user has been added to the queue
            if (!$match) {
                return response()->json([
                    'success' => true,
                    'status' => 'searching',
                    'message' => 'Waiting for an opponent...'
                ]);
            }

            // If $match exists, it means a PVP match was created or it's PVE
            return response()->json([
                'success' => true,
                'status' => 'match_found',
                'match_uuid' => $match->match_uuid
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function confirm(string $uuid, Request $request): JsonResponse
    {
        $user = $request->user();

        $match = ActiveMatch::where('match_uuid', $uuid)
            ->where('status', MatchStatus::PENDING)
            ->firstOrFail();

        // 1. Set ready to true for player that sends the request
        if ($match->player_1_id === $user->id) {
            $match->p1_ready = true;
        } elseif ($match->player_2_id === $user->id) {
            $match->p2_ready = true;
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $match->save();

        // 2. Are they both ready? If yes, change status to LOADING and notify players
        if ($match->p1_ready && $match->p2_ready) {

            $match->status = MatchStatus::LOADING;

            $p1 = User::find($match->player_1_id);
            $p2 = User::find($match->player_2_id);

            $match->p1_ready = $p1->is_bot;
            $match->p2_ready = $p2->is_bot;
            $match->save();

            // 3. Notify human players that the match is ready (Bots are auto-ready, so no need to notify them)
            if (!$p1->is_bot) {
                broadcast(new MatchReady($p1->id, $match->match_uuid));
            }
            if (!$p2->is_bot) {
                broadcast(new MatchReady($p2->id, $match->match_uuid));
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Endpoint: POST /api/matchmaking/cancel
     * Manually leave the queue or cancel a pending match.
     */
    public function cancel(Request $request): JsonResponse
    {
        $this->handleUserDisconnection($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Queue left and pending matches cancelled.'
        ]);
    }

    public function handleUserDisconnection(User $user): void
    {
        DB::transaction(function () use ($user) {
            // 1. Bloqueo y eliminación de la cola
            MatchmakingQueue::where('user_id', $user->id)->lockForUpdate()->delete();

            // 2. Gestión de partidas PENDING
            $pendingMatch = ActiveMatch::where('status', MatchStatus::PENDING)
                ->where(function ($query) use ($user) {
                    $query->where('player_1_id', $user->id)
                        ->orWhere('player_2_id', $user->id);
                })
                ->lockForUpdate()
                ->first();

            if ($pendingMatch) {
                $opponentId = ($pendingMatch->player_1_id === $user->id)
                    ? $pendingMatch->player_2_id
                    : $pendingMatch->player_1_id;

                $opponent = User::find($opponentId);

                if ($opponent && !$opponent->is_bot) {
                    // Avisamos al rival
                    broadcast(new MatchCancelledEvent($opponent->id));

                    // RE-COLA AUTOMÁTICA: Lo devolvemos a la cola con su prioridad
                    MatchmakingQueue::updateOrCreate(
                        ['user_id' => $opponentId],
                        ['game_mode' => $pendingMatch->game_mode->value, 'joined_at' => now()]
                    );
                }
                $pendingMatch->delete();
            }
        });
    }

}
