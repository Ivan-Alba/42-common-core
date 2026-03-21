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

class TurnTimeoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $matchUuid,
        protected string $expectedPlayerId
    ) {}

    public function handle(): void
    {
        $match = ActiveMatch::where('match_uuid', $this->matchUuid)->first();

        // 1. Safety checks
        if (!$match || $match->status !== 'playing') {
            return;
        }

        // 2. Check if it's still the turn of the same player
        // This prevents double-playing if the player acted at the last millisecond
        if ((string)$match->current_turn_player_id === $this->expectedPlayerId) {
            
            Log::info("[Job] Turn timeout for player {$this->expectedPlayerId} in match {$this->matchUuid}");

            // 3. Broadcast the force play event
            broadcast(new ForcePlayCardEvent($this->matchUuid, $this->expectedPlayerId));
            
            // Note: In the future, you will add the logic to pick a random card 
            // and update the database state here.
        }
    }
}
