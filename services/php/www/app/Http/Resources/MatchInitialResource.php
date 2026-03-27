<?php

namespace App\Http\Resources;

use App\Enums\GameMode;
use App\Services\MatchConfigProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchInitialResource extends JsonResource
{
    protected $authUserId;
    protected $serverNow;

    /**
     * @param mixed $resource The ActiveMatch model
     * @param int $authUserId The ID of the user requesting the data
     * @param float $serverNow Current server timestamp with microsecond precision
     */
    public function __construct($resource, $authUserId, $serverNow)
    {
        parent::__construct($resource);
        $this->authUserId = $authUserId;
        $this->serverNow = $serverNow;
    }

    public function toArray(Request $request): array
    {
        $isPlayer1 = (int) $this->authUserId === (int) $this->player_1_id;
        $localUser = $isPlayer1 ? $this->player1 : $this->player2;
        $opponentUser = $isPlayer1 ? $this->player2 : $this->player1;

        $isCampaign = in_array($this->game_mode, [
            GameMode::CAMPAIGN_1,
            GameMode::CAMPAIGN_2,
            GameMode::CAMPAIGN_3,
            GameMode::PVE
        ]);

        $config = MatchConfigProvider::getConfig($this->game_mode);
        $config['first_player_id'] = (int) $this->first_player_id;

        return [
            'match_id' => $this->match_uuid,
            'server_timestamp' => (float) $this->serverNow,
            'language' => $localUser->language ?? 'en',
            'config' => $config,
            'local_player' => $this->formatPlayerData($localUser, false),
            'opponent' => $this->formatPlayerData($opponentUser, $isCampaign),
        ];
    }

    protected function formatPlayerData($user, bool $isAi): ?array
    {
        if (!$user)
            return null;

        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'is_ai' => $isAi,
            'collection_ids' => $user->cards->pluck('id')->map(fn($id) => (int) $id)->toArray(),
        ];
    }
}
