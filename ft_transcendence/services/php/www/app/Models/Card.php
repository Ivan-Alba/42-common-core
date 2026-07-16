<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\CardTranslation;
use App\Enums\CardCategory;

class Card extends Model
{
    protected $fillable = [
        'name', 'description', 'category', 'blue_artwork', 'red_artwork', 
        'top', 'bottom', 'left', 'right', 'rarity'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'category' => CardCategory::class,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_user');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CardTranslation::class);
    }

    /**
    * Helper to get translation based on a locale
    */
    public function getTranslation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
}
