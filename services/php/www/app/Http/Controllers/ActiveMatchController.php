<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActiveMatch;
use App\Models\Matches;
use App\Models\User;
use App\Events\RivalReadyEvent;
use App\Events\RivalCardCountEvent;
use App\Events\MatchStartEvent;
use App\Events\PlayCardResponseEvent;
use App\Jobs\ForceSelectionTimeout;
use App\Jobs\TurnTimeoutJob;
use App\Http\Resources\MatchInitialResource;
use App\Enums\GameMode;
use App\Services\MatchLogicEngine;
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
        $match = ActiveMatch::where('match_uuid', $matchUuid)->firstOrFail();
        $serverNow = microtime(true);
        $gracePeriod = 1.0;

        if ($match->status === 'selecting' && $match->next_timeout_at === null) {
            $config = $match->GetMatchConfigAttribute();
            $delay = (int) ($config['selection_time_limit'] ?? 60);

            // Calculamos el final real
            $expiration = $serverNow + $delay;

            // El Watchdog espera un poco más (Grace Period)
            ForceSelectionTimeout::dispatch($match->match_uuid)
                ->delay(now()->addSeconds($delay + $gracePeriod));

            $match->update(['next_timeout_at' => $expiration]);
        }

        // Asegúrate de que tu MatchInitialResource use $serverNow para 'server_timestamp'
        return response()->json([
            'success' => true,
            'data' => new MatchInitialResource($match, $request->user()->id, $serverNow)
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
        $rivalId = ($senderId === (int) $match->player_1_id)
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
        $cardIds = array_map('intval', (array) $cardIds);

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
            broadcast(new RivalReadyEvent($matchUuid, $targetUser->id))->toOthers();
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
        $startDelay = 2.0; // Transition buffer to allow clients to process the "ready" state and prepare for the match start
        $gracePeriod = 1.0; // Latency buffer to ensure clients are ready before the first turn timer starts

        $config = $match->GetMatchConfigAttribute();
        $turnDuration = $config['turn_time_limit'] ?? 30;

        $turnStartTime = $serverNow + $startDelay;
        $turnEndTime = $turnStartTime + $turnDuration;

        // Update the next timeout for the first turn watchdog
        $match->next_timeout_at = $turnEndTime;

        // Trigger Match Start Event (Event 2)
        broadcast(new MatchStartEvent(
            $match->match_uuid,
            $match->first_player_id,
            $turnStartTime,
            $turnEndTime
        ));

        // Dispatch the first Turn Timeout Job
        TurnTimeoutJob::dispatch($match->match_uuid, $match->first_player_id)
            ->delay(now()->addSeconds(($turnEndTime - $serverNow) + $gracePeriod));
    }


    /**
     * Executes a card placement move, calculates captures, and schedules the next timeout.
     * * @param Request $request [player_id, card_id, board_index]
     * @param string $matchUuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function playCard(Request $request, $matchUuid)
    {
        return DB::transaction(function () use ($request, $matchUuid) {
            $match = ActiveMatch::where('match_uuid', $matchUuid)
                ->lockForUpdate()
                ->firstOrFail();

            // 1. Basic Validations
            if ($match->current_turn_player_id !== (int) $request->player_id) {
                return response()->json(['success' => false, 'error' => 'Not your turn'], 403);
            }

            $board = $match->board_state;
            if ($board[$request->board_index] !== null) {
                return response()->json(['success' => false, 'error' => 'Slot occupied'], 400);
            }

            $hands = $match->hands_state;
            $playerKey = ($match->player_1_id == $request->player_id) ? 'p1' : 'p2';

            if (!in_array($request->card_id, $hands[$playerKey])) {
                return response()->json(['success' => false, 'error' => 'Card not in hand'], 400);
            }

            // 2. Timing and State Preparation
            $serverNow = microtime(true); // Single source of time for this transaction

            $hands[$playerKey] = array_values(array_diff($hands[$playerKey], [$request->card_id]));
            $board[$request->board_index] = [
                'card_id' => (int) $request->card_id,
                'owner_id' => (int) $request->player_id
            ];

            // 3. Capture Logic Calculation
            $result = MatchLogicEngine::calculateMove($match, $board, $request->board_index, (int) $request->player_id);

            // 4. Dynamic Timing Calculation
            $animationDelaySeconds = $this->calculateAnimationsDelay($result['steps']);
            $config = $match->GetMatchConfigAttribute();
            $turnLimit = $config['turn_time_limit'] ?? 30;
            $gracePeriod = 1.0; // Margin for network latency before ForcePlay

            // Next turn starts after animations finish
            $nextTurnStartTime = $serverNow + $animationDelaySeconds;
            // Turn ends exactly after the limit, starting from the moment animations finish
            $turnEndTime = $nextTurnStartTime + $turnLimit;

            // 5. Determine Game State
            $gameStatus = $this->checkGameOver($result['new_board'], $match);
            $isMatchOver = $gameStatus['is_over'];
            $nextPlayerId = ($match->player_1_id == $request->player_id) ? $match->player_2_id : $match->player_1_id;

            // 6. Persistence
            $match->board_state = $result['new_board'];
            $match->hands_state = $hands;

            if ($isMatchOver) {
                $match->current_turn_player_id = null;
                $match->next_timeout_at = null;
                $match->save();

                $this->finalizeMatch($match, $gameStatus['scores'], $gameStatus['winner_id']);
            } else {
                $match->current_turn_player_id = $nextPlayerId;
                $match->next_timeout_at = $turnEndTime;
                $match->save();

                // The Job executes AFTER turnEndTime + gracePeriod
                TurnTimeoutJob::dispatch($match->match_uuid, $nextPlayerId)
                    ->delay(now()->addSeconds(($turnEndTime - $serverNow) + $gracePeriod));
            }

            // 7. BROADCAST DATA: The "Single Source of Truth" for Unity
            $payload = [
                'player_id' => (int) $request->player_id,
                'card_id' => (int) $request->card_id,
                'board_index' => (int) $request->board_index,
                'animation_steps' => $result['steps'],
                'match_over' => $isMatchOver,
                'next_turn_start_time' => $nextTurnStartTime,
                'turn_end_time' => $turnEndTime,
            ];

            broadcast(new PlayCardResponseEvent($matchUuid, $payload));

            // 8. Minimal Response
            return response()->json(['success' => true]);
        });
    }

    /**
     * Calculates the total time (in seconds) required for the client to perform all animations.
     * This ensures the server-side turn timer starts exactly when the client is ready.
     * * @param array $steps The animation steps generated by the MatchLogicEngine
     * @return float Total delay in seconds
     */
    private function calculateAnimationsDelay(array $steps): float
    {
        // 1. Basic configuration (Must match Unity's Animation Constants)
        $networkBuffer = 0.2;     // Margin for WebSocket propagation and processing
        $placementDelay = 0.5;    // Time for the card to travel from hand to board
        $baseFlipDelay = 0.5;     // Standard flip duration (Normal capture)
        $specialRuleExtra = 0.5;  // Extra time for Plus/Same/Combo (Scale/Particle effects)

        // Start with the mandatory delays
        $totalDelay = $networkBuffer + $placementDelay;

        // 2. Add cumulative time for each capture step
        foreach ($steps as $step) {
            $totalDelay += $baseFlipDelay;

            // If the rule is not 'normal', it involves special UI feedback in Unity
            if (isset($step['rule']) && strtolower($step['rule']) !== 'normal') {
                $totalDelay += $specialRuleExtra;
            }
        }

        return (float) $totalDelay;
    }

    /**
     * Checks if the board is full and returns the current game standing.
     * * @param array $board
     * @param ActiveMatch $match
     * @return array [is_over, scores]
     */
    private function checkGameOver(array $board, ActiveMatch &$match): array
    {
        // 1. If there's any empty slot, the game is not over
        if (in_array(null, $board, true)) {
            return ['is_over' => false, 'scores' => null];
        }

        $match->status = 'finished';

        // 2. Initial count from the board cards
        $p1Count = 0;
        $p2Count = 0;

        foreach ($board as $slot) {
            if ($slot && isset($slot['owner_id'])) {
                if ($slot['owner_id'] == $match->player_1_id)
                    $p1Count++;
                elseif ($slot['owner_id'] == $match->player_2_id)
                    $p2Count++;
            }
        }

        // 3. Add the unplayed card from the players' hands
        // In a standard match, one player will have 0 cards and the other will have 1.
        $firstPlayerId = $match->first_player_id;
        if ($firstPlayerId == $match->player_1_id) {
            $p2Count += 1;
        } else {
            $p1Count += 1;
        }


        // 4. Determine the winner based on the final 10-point total
        $winnerId = null;
        if ($p1Count > $p2Count) {
            $winnerId = $match->player_1_id;
        } elseif ($p2Count > $p1Count) {
            $winnerId = $match->player_2_id;
        }

        return [
            'is_over' => true,
            'winner_id' => $winnerId,
            'scores' => ['p1' => $p1Count, 'p2' => $p2Count]
        ];
    }

    /**
     * Moves the match data to the history table and performs cleanup.
     * * @param ActiveMatch $match
     * @param array $scores
     * @return void
     */
    private function finalizeMatch(ActiveMatch $match, array $scores, ?int $winner_id): void
    {
        DB::transaction(function () use ($match, $scores, $winner_id) {
            Matches::create([
                'match_uuid' => $match->match_uuid,
                'player_1_id' => $match->player_1_id,
                'player_2_id' => $match->player_2_id,
                'winner_id' => $winner_id,
                'game_mode' => $match->game_mode,
                'p1_score' => $scores['p1'],
                'p2_score' => $scores['p2'],
                'played_at' => $match->created_at,
            ]);

            // Add rewards logic here
            $this->distributeRewards($match, $winner_id);

            $match->delete();
            Log::info("[Match] History recorded and active session cleared for {$match->match_uuid}");
        });
    }


    /**
     * Calculates and grants XP and Ranked Points based on match outcome.
     * Handles level-up logic recursively.
     * * @param ActiveMatch $match
     * @param int|null $winnerId
     * @return void
     */
    private function distributeRewards(ActiveMatch $match, ?int $winnerId): void
    {
        $config = MatchConfigProvider::getConfig($match->game_mode);
        $rewards = $config['rewards'] ?? null;

        if (!$rewards)
            return;

        $playerIds = [
            'p1' => $match->player_1_id,
            'p2' => $match->player_2_id
        ];

        foreach ($playerIds as $playerId) {
            $user = User::with('stats')->find($playerId);

            // Skip bots and users without stats record
            if (!$user || $user->is_bot || !$user->stats)
                continue;

            $stats = $user->stats;
            $xpGain = 0;
            $rankedGain = 0;

            // 1. Determine Win/Loss/Draw and update counters
            if ($winnerId === null) {
                $xpGain = $rewards['draw_xp'];
                $rankedGain = $rewards['draw_ranked_points'];
                $stats->increment('draws');
            } elseif ((int) $winnerId === (int) $playerId) {
                $xpGain = $rewards['win_xp'];
                $rankedGain = $rewards['win_ranked_points'];
                $stats->increment('wins');
            } else {
                $xpGain = $rewards['loss_xp'];
                $rankedGain = $rewards['loss_ranked_points'];
                $stats->increment('losses');
            }

            // 2. Update Ranked Points (Ensure it doesn't drop below 0)
            $stats->ranked_points = max(0, $stats->ranked_points + $rankedGain);

            // 3. Process XP and Level Up Logic
            $stats->experience += $xpGain;

            // Loop in case the XP gained triggers multiple level ups
            while (true) {
                // Formula: 100 * (level ^ 1.5)
                $xpRequired = (int) (100 * pow($stats->level, 1.5));

                if ($stats->experience >= $xpRequired) {
                    $stats->experience -= $xpRequired;
                    $stats->level += 1;
                    Log::info("[Rewards] User {$playerId} leveled up to {$stats->level}!");
                } else {
                    break;
                }
            }

            $stats->save();

            Log::info("[Rewards] Processed for User {$playerId}: +{$xpGain}XP, +{$rankedGain} Rank.");
        }
    }

}
