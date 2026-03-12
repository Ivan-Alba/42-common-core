<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Card extends Model
{
    protected $fillable = [
        'name', 'description', 'back_image', 'front_image', 
        'top', 'bottom', 'left', 'right', 'rarity'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_user');
    }
}
