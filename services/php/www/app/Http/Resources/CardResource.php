<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'back_image' => $this->back_image,
            'front_image' => $this->front_image,
            'rarity' => $this->rarity,
            'stats' => [
                'top'    => $this->top,
                'bottom' => $this->bottom,
                'left'   => $this->left,
                'right'  => $this->right,
            ],
        ];
    }
}
