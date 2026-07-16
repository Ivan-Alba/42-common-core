<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\GameMode;

class MatchmakingQueue extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'matchmaking_queue';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'game_mode',
        'joined_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'game_mode' => GameMode::class,
        'joined_at' => 'datetime',
    ];

    /**
     * Get the user that is waiting in the queue.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
