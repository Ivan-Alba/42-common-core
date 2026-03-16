<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchInitialResource extends JsonResource
{
    protected $authUserId;

    public function __construct($resource, $authUserId)
    {
        parent::__construct($resource);
        $this->authUserId = $authUserId;
    }

    public function toArray(Request $request): array
    {
        $isPlayer1 = $this->authUserId == $this->player_1_id;
        
        $localUser = $isPlayer1 ? $this->player1 : $this->player2;
        $opponentUser = $isPlayer1 ? $this->player2 : $this->player1;

        return [
            'match_id' => (string) $this->id,
            'server_timestamp' => now()->timestamp,
            'language' => $localUser->language ?? 'en', 
            
            'config' => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 30,
                'selection_time_limit' => 40,
                'max_deck_cost' => 6,
                'rules' => ['open', 'same', 'plus', 'combo'],
                'first_player_id' => (string) $this->player_1_id,
            ],

            'local_player' => $this->formatPlayerData($localUser, false),
            'opponent' => $this->formatPlayerData($opponentUser, $this->is_vs_ai && $isPlayer1),
        ];
    }

    protected function formatPlayerData($user, $isAi)
    {
        if (!$user) return null;

        return [
            'id' => (string) $user->id,
            'name' => $user->name,
            'avatar_url' => $user->avatar,
            'is_ai' => $isAi,
            'collection_ids' => $user->cards->pluck('id')->map(fn($id) => (string)$id)->toArray(),
        ];
    }
}
