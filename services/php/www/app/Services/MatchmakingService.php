<?php

namespace App\Services;

use App\Models\User;
use App\Models\ActiveMatch;
use App\Enums\GameMode;
use App\Enums\UserStatus;
use App\Http\Controllers\ActiveMatchController;

class MatchmakingService
{
    /**
     * Finds or creates a match. For PVE, it forces a match with the bot.
     */
    public function findOrCreateMatch(User $user, GameMode $mode): ActiveMatch
    {
        // For PVE/Campaign modes
        if ($this->isPveMode($mode)) {
            // We use the ActiveMatchController's logic to maintain consistency
            $controller = new ActiveMatchController();
            $bot = User::where('is_bot', true)->firstOrFail();

            // Change user status to PLAYING
            $user->update(['status' => UserStatus::PLAYING]);

            return $controller->createMatch($user->id, $bot->id, $mode);
        }

        // Placeholder for future PVP logic
        throw new \Exception("PVP Matchmaking not implemented yet.");
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
