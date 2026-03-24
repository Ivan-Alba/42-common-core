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
        $baseUrl = asset('storage/cards/');

        $userLanguage = $request->query('lang') 
            ?? $request->user()?->language->value 
            ?? Language::ENGLISH->value;

        $translation = $this->translations->first(function ($item) use ($userLanguage) {
            return $item->getAttributes()['language'] === $userLanguage;
        });

        if (!$translation && $userLanguage !== Language::ENGLISH->value) {
            $translation = $this->translations->first(function ($item) {
                return $item->getAttributes()['language'] === Language::ENGLISH->value;
            });
        }

        return [
            'id' => (int) $this->id,
            'name' => $translation ? $translation->name : $this->name,
            'description' => $translation ? $translation->description : $this->description,
            'category' => $this->category,
            'blue_artwork' => $this->blue_artwork,
            'red_artwork' => $this->red_artwork,
            'blue_url' => "{$baseUrl}/{$this->blue_artwork}.png",
            'red_url' => "{$baseUrl}/{$this->red_artwork}.png",
            'rarity' => $this->rarity,
            'stats' => [
                'top'    => (int) $this->top,
                'bottom' => (int) $this->bottom,
                'left'   => (int) $this->left,
                'right'  => (int) $this->right,
            ],
        ];
    }
}
