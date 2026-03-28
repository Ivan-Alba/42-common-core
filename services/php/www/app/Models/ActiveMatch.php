<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\GameMode;
use App\Enums\MatchStatus;
use App\Services\MatchConfigProvider;
use Illuminate\Support\Str;

class ActiveMatch extends Model
{
    use HasFactory;

    protected $table = 'active_matches';

    protected $fillable = [
        'match_uuid',
        'player_1_id',
        'player_2_id',
        'game_mode',
        'status',
        'first_player_id',
        'current_turn_player_id',
        'board_state',
        'hands_state',
        'p1_ready',
        'p2_ready',
        'next_timeout_at',
    ];

    protected $casts = [
        'game_mode' => GameMode::class,
        'status' => MatchStatus::class,
        'board_state' => 'array',
        'hands_state' => 'array',
        'p1_ready' => 'boolean',
        'p2_ready' => 'boolean',
    ];

    /**
     * Boot function to auto-generate UUID on creation.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->match_uuid)) {
                $model->match_uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Accessor: $match->match_config
     * Bridges the model with the MatchConfigProvider service.
     */
    public function getMatchConfigAttribute(): array
    {
        return MatchConfigProvider::getConfig($this->game_mode);
    }

    // --- Relationships ---

    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_1_id');
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_2_id');
    }

    public function currentTurnPlayer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_turn_player_id');
    }
}
