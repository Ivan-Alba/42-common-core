<?php

namespace App\Http\Resources;

use App\Enums\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'id' => $this->id,
        //     'username' => $this->name,
        //     'avatar' => $this->avatar,
        //     'bio' => $this->bio,
        // ];
		return [
            'id' => $this->id,
            'username' => $this->name ?? $this->username, 
            'email' => $this->email,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'experience' => $this->experience,
            'pivot' => $this->pivot ?? null,
        ];
    }
}
