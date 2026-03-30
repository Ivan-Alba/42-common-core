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
     * Handles the logic for pairing players or adding them to the queue.
     *
     * @param User $user The user requesting matchmaking.
     * @param GameMode $mode The selected game mode (Casual, Ranked, etc.).
     * @return ActiveMatch|null Returns the created match or null if the user is now waiting.
     */
    private function handlePvpMatchmaking(User $user, GameMode $mode): ?ActiveMatch
    {
        // 1. ATTEMPT TO FIND AN OPPONENT (Atomic Transaction)
        // We use a transaction to ensure that two simultaneous processes
        // don't try to "claim" the same waiting opponent.
        $match = DB::transaction(function () use ($user, $mode) {

            $opponentEntry = MatchmakingQueue::where('game_mode', $mode->value)
                ->where('user_id', '!=', $user->id)
                // lockForUpdate() prevents other database queries from reading or
                // modifying this row until the transaction is committed or rolled back.
                ->lockForUpdate()
                ->orderBy('joined_at', 'asc')
                ->first();

            if ($opponentEntry) {
                $opponentId = $opponentEntry->user_id;

                // Remove the opponent from the queue immediately
                $opponentEntry->delete();

                // Instantiate the controller and create the persistent match record
                $controller = new ActiveMatchController();
                return $controller->createMatch($opponentId, $user->id, $mode);
            }

            return null; // No opponent found in this transaction block
        });

        // 2. POST-TRANSACTION LOGIC
        // If a match was created, we handle external side effects like broadcasting.
        if ($match) {
            $playerA = User::find($match->player_1_id);
            $playerB = User::find($match->player_2_id);

            // Notify both players via WebSockets (Reverb/Pusher)
            broadcast(new MatchFound($match->player_1_id, $playerB, $match->match_uuid));
            broadcast(new MatchFound($match->player_2_id, $playerA, $match->match_uuid));

            return $match;
        }

        // 3. FALLBACK: ENQUEUE THE PLAYER
        // If no opponent was found, we add the current user to the queue.
        // This is done outside the previous transaction to ensure immediate visibility
        // for other incoming matchmaking requests.
        MatchmakingQueue::updateOrCreate(
            ['user_id' => $user->id],
            [
                'game_mode' => $mode->value,
                'joined_at' => now()
            ]
        );

        return null; // Signals the caller that the player is now in the queue
    }

    private function createBotMatch(User $user, GameMode $mode): ActiveMatch
    {
        $controller = new ActiveMatchController();
        $bot = User::where('is_bot', true)->firstOrFail();

        $match = $controller->createMatch($user->id, $bot->id, $mode);

        $match->p2_ready = true; //TODO WHEN PENDING/LOADING STATE IMPLEMENTED, SET BOT AS READY AUTOMATICALLY
        $match->save();

        broadcast(new MatchFound($user->id, $bot, $match->match_uuid));

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
