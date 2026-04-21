<?php

namespace App\Http\Resources;

use App\Enums\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 1. Determine the language priority (Same as CardResource)
        $userLanguage = $request->query('lang')
            ?? $request->user()?->language->value
            ?? Language::ENGLISH->value;

        // 2. Find the translation that matches the language
        // We use getAttributes()['locale'] because that's how we named it in AchievementTranslation
        $translation = $this->translations->first(function ($item) use ($userLanguage) {
            return $item->getAttributes()['locale'] === $userLanguage;
        });

        // 3. Fallback to English if not found
        if (!$translation && $userLanguage !== Language::ENGLISH->value) {
            $translation = $this->translations->first(function ($item) {
                return $item->getAttributes()['locale'] === Language::ENGLISH->value;
            });
        }

        /**
         * Since we are loading all achievements and filtering the 'users' relationship
         * for the specific user in the Controller.
         */
        $userProgress = $this->users->first();

        return [
            'id' => (int) $this->id,
            'code' => $this->code,
            'category' => $this->category->value,
            'title' => $translation ? $translation->title : 'Untranslated Achievement',
            'description' => $translation ? $translation->description : 'No description available',
            'goal' => (int) $this->goal,
            'points_reward' => (int) $this->points,

            // Progress logic from the pivot table
            'current_progress' => $userProgress ? (int) $userProgress->pivot->progress : 0,
            'unlocked_at' => $userProgress ? $userProgress->pivot->unlocked_at : null,
            'is_unlocked' => $userProgress ? !is_null($userProgress->pivot->unlocked_at) : false,

            // Reward information
            'card_reward_id' => $this->card_reward_id ? (int) $this->card_reward_id : null,
        ];
    }
}
