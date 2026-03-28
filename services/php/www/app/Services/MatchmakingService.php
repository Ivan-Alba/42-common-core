<?php

namespace App\Services;

use App\Models\User;
use App\Models\ActiveMatch;
use App\Models\MatchmakingQueue;
use App\Enums\GameMode;
use App\Enums\MatchStatus;
use App\Http\Controllers\ActiveMatchController;
use App\Events\MatchFound;
use Illuminate\Support\Facades\DB;

class MatchmakingService
{
    /**
     * Finds or creates a match.
     * Returns ActiveMatch if a match is created, or null if the user is queued.
     */
    public function findOrCreateMatch(User $user, GameMode $mode): ?ActiveMatch
    {
        // 1. Handle PVE/Campaign modes (Instant match with Bot)
        if ($this->isPveMode($mode)) {
            return $this->createBotMatch($user, $mode);
        }

        // 2. Handle PVP Matchmaking
        return $this->handlePvpMatchmaking($user, $mode);
    }

    /**
     * Logic to match two humans or put the player in the queue.
     */
    private function handlePvpMatchmaking(User $user, GameMode $mode): ?ActiveMatch
    {
        return DB::transaction(function () use ($user, $mode) {
            // Check if there is someone waiting for the same mode
            $opponentEntry = MatchmakingQueue::where('game_mode', $mode->value)
                ->where('user_id', '!=', $user->id)
                ->orderBy('joined_at', 'asc')
                ->first();

            if ($opponentEntry) {
                $opponentId = $opponentEntry->user_id;
                $opponentEntry->delete();

                $controller = new ActiveMatchController();
                $match = $controller->createMatch($opponentId, $user->id, $mode);

                broadcast(new MatchFound($opponentId, $match->match_uuid));
                broadcast(new MatchFound($user->id, $match->match_uuid));

                return $match;
            }

            // NO MATCH FOUND: Add current user to the queue if not already there
            MatchmakingQueue::updateOrCreate(
                ['user_id' => $user->id],
                ['game_mode' => $mode->value, 'joined_at' => now()]
            );

            return null; // Signals the controller that the user is now waiting
        });
    }

    private function createBotMatch(User $user, GameMode $mode): ActiveMatch
    {
        $controller = new ActiveMatchController();
        $bot = User::where('is_bot', true)->firstOrFail();

        $match = $controller->createMatch($user->id, $bot->id, $mode);

        $match->p2_ready = true; //TODO WHEN PENDING/LOADING STATE IMPLEMENTED, SET BOT AS READY AUTOMATICALLY
        $match->save();

        broadcast(new MatchFound($user->id, $match->match_uuid));

        return $match;
    }

    private function isPveMode(GameMode $mode): bool
    {
        return in_array($mode, [
            GameMode::CAMPAIGN_1,
            GameMode::CAMPAIGN_2,
            GameMode::CAMPAIGN_3,
            GameMode::PVE
        ]);
    }
}
