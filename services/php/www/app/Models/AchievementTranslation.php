<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AchievementTranslation extends Model
{
    // We don't need timestamps for translations usually, but your migration doesn't have them anyway
    public $timestamps = false;

    protected $fillable = [
        'achievement_id',
        'locale',
        'title',
        'description',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }
}
