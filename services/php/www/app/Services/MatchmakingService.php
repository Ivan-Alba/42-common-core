<?php

namespace App\Services;

use App\Models\User;
use App\Models\ActiveMatch;
use App\Enums\GameMode;
use App\Http\Controllers\ActiveMatchController;

class MatchmakingService
{
    // Keeping it simple: The master bot is always ID 1
    private const BOT_USER_ID = 1;

    /**
     * Finds or creates a match. For PVE, it forces a match with the bot.
     */
    public function findOrCreateMatch(User $user, GameMode $mode): ActiveMatch
    {
        // For PVE/Campaign modes
        if ($this->isPveMode($mode)) {
            // We use the ActiveMatchController's logic to maintain consistency
            $controller = new ActiveMatchController();
            return $controller->createMatch($user->id, self::BOT_USER_ID, $mode);
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
