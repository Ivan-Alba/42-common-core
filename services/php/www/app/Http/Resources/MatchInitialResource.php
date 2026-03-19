<?php

namespace App\Http\Resources;

use App\Enums\GameMode;
use App\Services\MatchConfigProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchInitialResource extends JsonResource
{
    protected $authUserId;

    /**
     * @param mixed $resource The ActiveMatch model
     * @param int $authUserId The ID of the user requesting the data
     */
    public function __construct($resource, $authUserId)
    {
        parent::__construct($resource);
        $this->authUserId = $authUserId;
    }

    public function toArray(Request $request): array
    {
        // 1. Identify roles
        $isPlayer1 = $this->authUserId == $this->player_1_id;
        $localUser = $isPlayer1 ? $this->player1 : $this->player2;
        $opponentUser = $isPlayer1 ? $this->player2 : $this->player1;

        // 2. Logic to determine if opponent is AI based on GameMode
        // You can expand this logic as you add more modes
        $isCampaign = in_array($this->game_mode, [
            GameMode::CAMPAIGN_1,
            GameMode::CAMPAIGN_2,
            GameMode::CAMPAIGN_3,
            GameMode::PVE
        ]);

        // 3. Get static config and merge dynamic data
        $config = MatchConfigProvider::getConfig($this->game_mode);
        $config['first_player_id'] = (string) $this->first_player_id;

        return [
            // Use match_uuid for external communication
            'match_id' => $this->match_uuid, 
            'server_timestamp' => now()->timestamp,
            'language' => $localUser->language ?? 'en', 
            
            'config' => $config,

            // If P1 is local and it's a campaign, the opponent (P2) is AI. 
            // If P2 is local and it's a campaign, the opponent (P1) is AI.
            'local_player' => $this->formatPlayerData($localUser, false),
            'opponent' => $this->formatPlayerData($opponentUser, $isCampaign),
        ];
    }

    /**
     * Standardizes player data for Unity consumption.
     */
    protected function formatPlayerData($user, bool $isAi): ?array
    {
        if (!$user) return null;

        return [
            'id' => (string) $user->id,
            'name' => $user->name,
            'avatar_url' => $user->avatar, // Match this with your User model attribute
            'is_ai' => $isAi,
            // Cast all IDs to string to prevent overflow/type issues in C#
            'collection_ids' => $user->cards->pluck('id')->map(fn($id) => (string)$id)->toArray(),
        ];
    }
}
