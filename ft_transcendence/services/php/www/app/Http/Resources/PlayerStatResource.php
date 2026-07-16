<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'level' => $this->level,
            'experience' => $this->experience,
            'achievement_points' => $this->achievement_points,
            'ranked_points' => $this->ranked_points,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'draws' => $this->draws,
            'campaign' => $this->campaign,
            'last_rank_pos' => $this->last_rank_pos,
        ];
    }
}
