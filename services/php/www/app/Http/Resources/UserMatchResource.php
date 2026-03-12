<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'player_1_id' => $this->player_1_id,
            'player_2_id' => $this->player_2_id,
            'player_1_name' => $this->player1?->name,
            'player_2_name' => $this->player2?->name,
            'winner_id' => $this->winner_id,
            'game_mode' => $this->game_mode,
            'is_vs_ai' => $this->is_vs_ai,
            'p1_score' => $this->p1_score,
            'p2_score' => $this->p2_score,
            'p1_points_earned' => $this->p1_points_earned,
            'p2_points_earned' => $this->p2_points_earned,
            'played_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
