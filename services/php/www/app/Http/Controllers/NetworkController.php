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

        if ($match && $match->status === 'finished') {
            return response()->json([
                'success' => true,
                'message' => 'Match already finished. No penalty applied.',
                'data' => [
                    'rival_disconnected' => false,
                    'own_disconnection' => true
                ]
            ]);
        }

        // Configuration thresholds
        $timeoutThreshold = 16.0;
        $loadingThreshold = 40.0;
        $maxInactivityThreshold = 30.0;
        $staleMatchThreshold = 10.0; // Margin after next_timeout_at before considering the match "dead"


        return DB::transaction(function () use ($match, $user, $request, $serverTime, $timeoutThreshold, $loadingThreshold, $maxInactivityThreshold, $staleMatchThreshold) {

            $match->lockForUpdate();

            $isP1 = (int) $user->id === (int) $match->player_1_id;
            $isP2 = (int) $user->id === (int) $match->player_2_id;

            if (!$isP1 && !$isP2) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // --- SECURITY BARRIER: OWN DISCONNECTION & STALE MATCH CHECK ---
            $ownLastPing = $isP1 ? $match->last_ping_p1 : $match->last_ping_p2;
            $isAlreadyMarkedDisconnected = $isP1 ? $match->p1_disconnected : $match->p2_disconnected;

            $isMatchStale = false;
            $hasInactivityTimeout = false;

            // Check if the match timer is stale (next_timeout_at + 10s has passed without updates)
            if ($match->status !== 'loading') {
                if ($match->next_timeout_at && ($serverTime > ($match->next_timeout_at + $staleMatchThreshold))) {
                    $isMatchStale = true;
                }

            // 2. Check for inactivity timeout
                if ($ownLastPing !== null && ($serverTime - $ownLastPing) > $maxInactivityThreshold) {
                    $hasInactivityTimeout = true;
                }
            }

            // Conditions to kick the player
            if ($isAlreadyMarkedDisconnected || $isMatchStale || $hasInactivityTimeout) {

                // Determine the reason for logging and frontend handling
                $reason = 'inactivity_timeout';
                if ($isAlreadyMarkedDisconnected)
                    $reason = 'marked_by_server';
                if ($isMatchStale)
                    $reason = 'stale_game_state';

                return response()->json([
                    'success' => true,
                    'data' => [
                        'own_disconnection' => true,
                        'reason' => $reason
                    ]
                ]);
            }

            // 2. Update the sender's last ping timestamp
            if ($isP1) {
                $match->last_ping_p1 = $serverTime;
            } else {
                $match->last_ping_p2 = $serverTime;
            }

            // 3. Identify the Rival and check if it's a Bot
            $rivalId = $isP1 ? $match->player_2_id : $match->player_1_id;
            $rival = User::find($rivalId);
            $isRivalBot = $rival && $rival->is_bot;

            // 4. Cross-check Connectivity
            $rivalAlreadyDisconnected = $isP1 ? $match->p2_disconnected : $match->p1_disconnected;

            if (!$isRivalBot && !$rivalAlreadyDisconnected) {
                $rivalLastPing = $isP1 ? $match->last_ping_p2 : $match->last_ping_p1;

                if ($rivalLastPing !== null) {
                    if (($serverTime - $rivalLastPing) > $timeoutThreshold) {
                        $this->markRivalAsDisconnected($match, $isP1);
                        Log::info("Match {$match->match_uuid}: Human Rival timed out.");
                    }
                } else {
                    $matchStartTime = (float) $match->created_at->getTimestamp();
                    if (($serverTime - $matchStartTime) > $loadingThreshold) {
                        $this->markRivalAsDisconnected($match, $isP1);
                        Log::warning("Match {$match->match_uuid}: Human Rival failed to load.");
                    }
                }
            }

            $match->save();

            // 5. Broadcast the pong event
            broadcast(new PongEvent(
                $user->id,
                (float) $request->client_timestamp,
                $serverTime
            ));

            // 6. Final Response
            return response()->json([
                'success' => true,
                'data' => [
                    'rival_disconnected' => $isP1 ? (bool) $match->p2_disconnected : (bool) $match->p1_disconnected,
                    'own_disconnection' => false
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
