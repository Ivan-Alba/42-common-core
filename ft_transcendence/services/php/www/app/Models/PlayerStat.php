<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PlayerStat extends Model
{
    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = [
        'user_id', 'level', 'experience', 'achievement_points', 'ranked_points', 
        'last_rank_pos', 'wins', 'losses', 'draws', 'campaign'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
