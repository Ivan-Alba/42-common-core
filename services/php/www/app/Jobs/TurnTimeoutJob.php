<?php

namespace App\Jobs;

use App\Models\ActiveMatch;
use App\Events\ForcePlayCardEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job responsible for handling turn timeouts.
 * It selects the first available card and slot, then notifies Unity
 * to force the execution of the move.
 */
class TurnTimeoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param string $matchUuid
     * @param int $expectedPlayerId
     */
    public function __construct(
        protected string $matchUuid,
        protected int $expectedPlayerId
    ) {
    }

    /**
     * Executes the timeout logic by selecting a fallback move.
     */
    public function handle(): void
    {
        $match = ActiveMatch::where('match_uuid', $this->matchUuid)->first();

        // 1. Safety Checks
        if (!$match || $match->status !== 'playing') {
            return;
        }

        // Verify if it's still the turn of the player we expected (using strict integer comparison)
        if ($match->current_turn_player_id !== $this->expectedPlayerId) {
            return;
        }

        // Verify if this Job corresponds to the current turn's timeout
        if ($match->next_timeout_at && now()->timestamp < ($match->next_timeout_at - 1)) {
            return;
        }

        // 2. Automated Selection Logic
        $hands = $match->hands_state;

        // Determine player key (p1 or p2) based on IDs
        $playerKey = ($match->player_1_id === $this->expectedPlayerId) ? 'p1' : 'p2';

        // Ensure the player has cards left in their hand state
        if (empty($hands[$playerKey])) {
            return;
        }

        // Select the first card available in hand
        $cardId = $hands[$playerKey][0];

        // Find the first empty slot on the board (null value)
        $board = $match->board_state;
        $boardIndex = -1;

        foreach ($board as $index => $slot) {
            if ($slot === null) {
                $boardIndex = $index;
                break;
            }
        }

        // Abort if no slot is found (edge case safety)
        if ($boardIndex === -1) {
            return;
        }

        Log::info("[Job] Turn timeout for player {$this->expectedPlayerId}. Forcing card {$cardId} at index {$boardIndex}");

        // 3. Notify Unity to execute the move
        // All values are sent as integers to match Unity's DTO
        broadcast(new ForcePlayCardEvent(
            $this->matchUuid,
            $this->expectedPlayerId,
            $cardId,
            $boardIndex
        ));

        Log::info("[Job Success] Broadcasting ForcePlayCardEvent...");
    }
}
