<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActiveMatch;
use App\Models\Card;
use App\Models\User;
use App\Events\RivalReadyEvent;
use App\Events\RivalCardCountEvent;
use App\Events\MatchStartEvent;
use App\Jobs\ForceSelectionTimeout;
use App\Jobs\TurnTimeoutJob;
use App\Http\Resources\MatchInitialResource;
use App\Enums\GameMode;
use App\Services\MatchConfigProvider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActiveMatchController extends Controller
{
    /**
     * Get initial data for Unity to boot the match.
     * Uses the match_uuid for lookup.
     */
    public function getMatchData(Request $request, string $matchUuid): JsonResponse
    {
        // 1. Eager load everything needed for the MatchInitialResource
        $match = ActiveMatch::with([
            'player1.cards', 
            'player2.cards'
        ])->where('match_uuid', $matchUuid)->firstOrFail();

        $authUserId = $request->user()->id; 

        // 2. Security check
        if ($authUserId != $match->player_1_id && $authUserId != $match->player_2_id) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized: You are not a participant in this match.'
            ], 403);
        }

        // 3. Trigger Selection Timeout Watchdog
        // We only dispatch it if the match is in 'selecting' and no timeout was set
        if ($match->status === 'selecting' && $match->next_timeout_at === null) {
            $config = $match->match_config;
            $delay = $config['selection_time_limit'];

            ForceSelectionTimeout::dispatch($match->match_uuid)
                ->delay(now()->addSeconds($delay));
            
            // We use next_timeout_at as a flag to know the timer is already running
            $match->update(['next_timeout_at' => now()->addSeconds($delay)->timestamp]);
        }

        return response()->json([
            'success' => true,
            'data' => new MatchInitialResource($match, $authUserId) 
        ]);
    }

    /**
     * Internal/Private function to create a new active match session.
     * This would typically be called by a Matchmaking Service.
     */
    public function createMatch(int $p1Id, int $p2Id, GameMode $mode): ActiveMatch
    {
        $p1 = User::find($p1Id);
        $p2 = User::find($p2Id);

        if ($p1->is_bot && !$p2->is_bot) {
            $firstPlayerId = $p2Id;
        } elseif (!$p1->is_bot && $p2->is_bot) {
            $firstPlayerId = $p1Id;
        } else {
            // Both are humans keep it random
            $firstPlayerId = collect([$p1Id, $p2Id])->random();
        }

        // Initialize an empty board state (9 slots for 3x3)
        $emptyBoard = array_fill(0, 9, null);

        // Initial hands state will be filled once players confirm their decks,
        // but we initialize the structure here.
        $initialHands = [
            'p1' => [],
            'p2' => []
        ];

        return ActiveMatch::create([
            'player_1_id' => $p1Id,
            'player_2_id' => $p2Id,
            'game_mode' => $mode,
            'status' => 'selecting',
            'first_player_id' => $firstPlayerId,
            'current_turn_player_id' => $firstPlayerId,
            'board_state' => $emptyBoard,
            'hands_state' => $initialHands,
            'p1_ready' => false,
            'p2_ready' => false,
            'next_timeout_at' => null // Set this after returning the first response
        ]);
    }

    /**
 * Updates the current card selection count and notifies the opponent.
 * Path: /api/matches/{matchUuid}/update-selection
 */
public function updateSelection(Request $request, $matchUuid)
{
    $request->validate([
        'player_id' => 'required|integer',
        'current_count' => 'required|integer',
    ]);

    $match = ActiveMatch::where('match_uuid', $matchUuid)->firstOrFail();
    
    $senderId = (int) $request->player_id;
    $currentCount = (int) $request->current_count;

    // 1. Identify who is the rival
    $rivalId = ($senderId === (int)$match->player_1_id) 
               ? $match->player_2_id 
               : $match->player_1_id;

    $rival = User::find($rivalId);

    // 2. Broadcast only if the rival is a human (not a bot)
    // and use "toOthers" so the person who sent the request doesn't receive it back
    if ($rival && !$rival->is_bot) {
        broadcast(new RivalCardCountEvent(
            $matchUuid, 
            $senderId, 
            $currentCount
        ))->toOthers();
    }

    return response()->json(['success' => true]);
}

/**
 * Handle deck confirmation for both human players and authorized bots.
 *
 * @param Request $request
 * @param string $matchUuid
 * @return JsonResponse
 */
public function confirmDeck(Request $request, string $matchUuid): JsonResponse
{
    $authUser = $request->user();
    $targetPlayerId = (int) $request->input('player_id'); // ID of the player performing the action
    $cardIds = $request->input('card_ids');
    $cardIds = array_map('intval', (array)$cardIds);

    $match = ActiveMatch::where('match_uuid', $matchUuid)->firstOrFail();
    $targetUser = User::findOrFail($targetPlayerId);

    // --- SECURITY GATE: Check if the action is authorized ---
    if ($authUser->id !== $targetUser->id) {
        // Only allow proxying actions if target is a bot within the same match
        $isBotInMatch = $targetUser->is_bot && 
                        ($targetUser->id === $match->player_1_id || $targetUser->id === $match->player_2_id) &&
                        ($authUser->id === $match->player_1_id || $authUser->id === $match->player_2_id);

        if (!$isBotInMatch) {
            return response()->json(['success' => false, 'error' => 'Unauthorized action.'], 403);
        }
    }

    // 1. Verify match phase
    if ($match->status !== 'selecting') {
        return response()->json(['success' => false, 'error' => 'Invalid match phase.'], 400);
    }

    // 2. Persist hand state and readiness
    $isP1 = $targetUser->id === $match->player_1_id;
    $hands = $match->hands_state ?? ['p1' => [], 'p2' => []];

    if ($isP1) {
        $match->p1_ready = true;
        $hands['p1'] = $cardIds;
    } else {
        $match->p2_ready = true;
        $hands['p2'] = $cardIds;
    }
    
    $match->hands_state = $hands;

    // 3. BROADCAST LOGIC: Only if BOTH players are humans
    // We check if the match is NOT a PvE match
    $player1 = User::find($match->player_1_id);
    $player2 = User::find($match->player_2_id);

    $isPvP = ($player1 && !$player1->is_bot) && ($player2 && !$player2->is_bot);

    if ($isPvP) {
        broadcast(new RivalReadyEvent($matchUuid, (string)$targetUser->id))->toOthers();
    }

    // 4. Trigger match transition if both participants are ready
    if ($match->p1_ready && $match->p2_ready) {
        $this->startMatchTransition($match);
    }

    $match->save();

    return response()->json(['success' => true]);
}

/**
 * Handles the logic when both players are ready.
 */
private function startMatchTransition(ActiveMatch $match)
{
    $match->status = 'playing';
    
    $serverNow = microtime(true);
    $startDelay = 2.0; // START_MATCH_DELAY
    $turnStartTime = $serverNow + $startDelay;
    
    // Update the next timeout for the first turn watchdog
    $turnDuration = $match->match_config['turn_time_limit'];
    $match->next_timeout_at = $turnStartTime + $turnDuration;

    // Trigger Match Start Event (Event 2)
    broadcast(new MatchStartEvent(
        $match->match_uuid,
        (string)$match->first_player_id,
        $serverNow,
        $turnStartTime
    ));

    // Dispatch the first Turn Timeout Job
    //TurnTimeoutJob::dispatch($match->match_uuid, (string)$match->first_player_id)
      //  ->delay(now()->addSeconds($startDelay + $turnDuration + 1));
}

}
