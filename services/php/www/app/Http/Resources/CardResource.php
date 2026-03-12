<?php

namespace App\Http\Resources;

use App\Enums\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userLanguage = $request->user()?->language->value ?? Language::ENGLISH->value;

        $translation = $this->translations->first(function ($item) use ($userLanguage) {
            return $item->getAttributes()['language'] === $userLanguage;
        });

        if (!$translation && $userLanguage !== Language::ENGLISH->value) {
            $translation = $this->translations->first(function ($item) {
                return $item->getAttributes()['language'] === Language::ENGLISH->value;
            });
        }

        return [
            'id' => $this->id,
            'name' => $translation ? $translation->name : $this->name,
            'description' => $translation ? $translation->description : $this->description,
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
