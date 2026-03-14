<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\CardTranslation;

class Card extends Model
{
    protected $fillable = [
        'id', 'name', 'description', 'front_image', 
        'top', 'bottom', 'left', 'right', 'rarity'
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
