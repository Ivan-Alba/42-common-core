<?php

namespace App\Http\Resources;

use App\Enums\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlayerStatResource;
use App\Http\Resources\UserMatchResource;

class UserResource extends JsonResource
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
            'username' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'language' => $this->language->value ?? Language::SPANISH->value,
            'is_bot' => $this->is_bot,
            'status' => $this->status,

            // Only if ->load('stats')
            'stats' => new PlayerStatResource($this->whenLoaded('stats')),

            // Only if injected
            'match_history' => UserMatchResource::collection($this->whenLoaded('match_history')),
        ];
    }
}
