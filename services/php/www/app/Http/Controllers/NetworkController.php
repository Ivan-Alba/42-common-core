<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Events\PongEvent;
use App\Models\ActiveMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NetworkController extends Controller
{
    /**
     * Receives a ping from Unity, updates the player's timestamp,
     * performs a cross-check on the rival's status (only for PvP), 
     * and returns a Pong response.
     */
    public function sendPong(Request $request): JsonResponse
    {
        $request->validate([
            'client_timestamp' => 'required|numeric',
            'match_uuid' => 'required|string',
        ]);

        $user = $request->user();
        $matchUuid = $request->match_uuid;
        $serverTime = microtime(true);

        $match = ActiveMatch::where('match_uuid', $matchUuid)->first();

        if (!$match) {
            return response()->json([
                'success' => true,
                'message' => 'Match already concluded or not found.',
                'data' => [
                    'rival_disconnected' => false
                ]
            ]);
        }

        // Configuration thresholds
        $timeoutThreshold = 16.0; // Standard margin for active players
        $loadingThreshold = 40.0; // Grace period for the first ping after creation

        return DB::transaction(function () use ($match, $user, $request, $serverTime, $timeoutThreshold, $loadingThreshold) {

            // 1. Lock the match row to prevent race conditions
            $match->lockForUpdate();

            $isP1 = (int) $user->id === (int) $match->player_1_id;
            $isP2 = (int) $user->id === (int) $match->player_2_id;

            if (!$isP1 && !$isP2) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // 2. Update the sender's last ping timestamp (Always, even in PvE)
            if ($isP1) {
                $match->last_ping_p1 = $serverTime;
            } else {
                $match->last_ping_p2 = $serverTime;
            }

            // 3. Identify the Rival and check if it's a Bot
            $rivalId = $isP1 ? $match->player_2_id : $match->player_1_id;
            $rival = User::find($rivalId);
            $isRivalBot = $rival && $rival->is_bot;

            // 4. Cross-check Connectivity (ONLY if the rival is NOT a Bot)
            $rivalAlreadyDisconnected = $isP1 ? $match->p2_disconnected : $match->p1_disconnected;

            if (!$isRivalBot && !$rivalAlreadyDisconnected) {
                $rivalLastPing = $isP1 ? $match->last_ping_p2 : $match->last_ping_p1;

                if ($rivalLastPing !== null) {
                    // CASE A: PvP Rival was active but stopped sending pings
                    if (($serverTime - $rivalLastPing) > $timeoutThreshold) {
                        $this->markRivalAsDisconnected($match, $isP1);
                        Log::info("Match {$match->match_uuid}: Human Rival timed out.");
                    }
                } else {
                    // CASE B: PvP Rival never sent a ping (Loading timeout)
                    $matchStartTime = (float) $match->created_at->getTimestamp();

                    if (($serverTime - $matchStartTime) > $loadingThreshold) {
                        $this->markRivalAsDisconnected($match, $isP1);
                        Log::warning("Match {$match->match_uuid}: Human Rival failed to load.");
                    }
                }
            }

            $match->save();

            // 5. Broadcast the pong event (RTT sync via WebSockets)
            broadcast(new PongEvent(
                $user->id,
                (float) $request->client_timestamp,
                $serverTime
            ));

            // 6. Final Response
            // Note: rival_disconnected will always be false for bots
            return response()->json([
                'success' => true,
                'data' => [
                    'rival_disconnected' => $isP1 ? (bool) $match->p2_disconnected : (bool) $match->p1_disconnected
                ]
            ]);
        });
    }

    /**
     * Helper to set the disconnection flag for the opponent.
     */
    private function markRivalAsDisconnected(ActiveMatch $match, bool $isP1): void
    {
        if ($isP1) {
            $match->p2_disconnected = true;
        } else {
            $match->p1_disconnected = true;
        }
    }
}
