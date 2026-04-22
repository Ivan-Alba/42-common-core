<?php

namespace App\Models;

use App\Enums\AchievementCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $fillable = [
        'code',
        'category',
        'goal',
        'points',
        'card_reward_id', 
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'category' => AchievementCategory::class,
    ];

    /**
     * Relationship with the card rewarded by this achievement.
     */
    public function cardReward(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_reward_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(AchievementTranslation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('progress', 'unlocked_at', 'claimed')
            ->withTimestamps();
    }

    /**
     * Helper to get translation based on a specific locale.
     *
     * @param string $locale
     * @return \App\Models\AchievementTranslation|null
     */
    public function getTranslation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
}
