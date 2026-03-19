<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActiveMatch;
use App\Models\Card;
use App\Jobs\ForceSelectionTimeout;
use App\Http\Resources\MatchInitialResource;
use App\Enums\GameMode;
use App\Services\MatchConfigProvider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
        // Note: Using a simple flag or checking if a job was already sent.
        // For now, we'll check status and a basic timestamp check to avoid duplicates.
        if ($match->status === 'selecting' && $match->next_timeout_at === null) {
            $config = $match->match_config;
            $delay = $config['selection_time_limit'] + 5; // 5s network buffer

            \App\Jobs\ForceSelectionTimeout::dispatch($match->match_uuid)
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
        // Randomly decide who starts the game
        $firstPlayerId = collect([$p1Id, $p2Id])->random();

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
            'current_turn_player_id' => $firstPlayerId, // Usually same as first player
            'board_state' => $emptyBoard,
            'hands_state' => $initialHands,
            'p1_ready' => false,
            'p2_ready' => false,
            'next_timeout_at' => null // Set this after returning the first response
        ]);
    }

/**
 * Unity calls this to confirm the selected deck.
 */
public function confirmDeck(Request $request, string $matchUuid): JsonResponse
{
    $user = $request->user();
    $cardIds = $request->input('card_ids'); // Array of card IDs

    $match = ActiveMatch::where('match_uuid', $matchUuid)->firstOrFail();

    // 1. Validation: Phase and Ownership
    if ($match->status !== 'selecting') {
        return response()->json(['success' => false, 'error' => 'Invalid phase.'], 400);
    }

    $userCardIds = $user->cards()->pluck('cards.id')->map(fn($id) => (string)$id)->toArray();
    foreach ($cardIds as $id) {
        if (!in_array($id, $userCardIds)) {
            return response()->json(['success' => false, 'error' => "Unauthorized card: $id"], 403);
        }
    }

    // 2. Validation: Deck Cost
    $config = $match->match_config;
    $totalCost = \App\Models\Card::whereIn('id', $cardIds)->get()->sum(fn($c) => $c->rarity->getCost());

    if ($totalCost > $config['max_deck_cost']) {
        return response()->json(['success' => false, 'error' => 'Deck cost exceeded.'], 400);
    }

    // 3. Update State
    $isP1 = $user->id === $match->player_1_id;
    $hands = $match->hands_state;
    
    if ($isP1) {
        $match->p1_ready = true;
        $hands['p1'] = $cardIds;
    } else {
        $match->p2_ready = true;
        $hands['p2'] = $cardIds;
    }
    $match->hands_state = $hands;

    // 4. Notify Rival (Event 1)
    // We notify the other player that this player is ready
    broadcast(new \App\Events\RivalReadyEvent($matchUuid, (string)$user->id))->toOthers();

    // 5. Transition to Combat (Event 2)
    if ($match->p1_ready && $match->p2_ready) {
        $this->startMatchTransition($match);
    }

    $match->save();

    return response()->json(['success' => true, 'data' => true]);
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
    broadcast(new \App\Events\MatchStartEvent(
        $match->match_uuid,
        (string)$match->first_player_id,
        $serverNow,
        $turnStartTime
    ));

    // Dispatch the first Turn Timeout Job
    \App\Jobs\TurnTimeoutJob::dispatch($match->match_uuid, (string)$match->first_player_id)
        ->delay(now()->addSeconds($startDelay + $turnDuration + 1));
}

}
