<?php

namespace App\Models;

use App\Enums\GameMode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'player_1_id', 
        'player_2_id', 
        'winner_id',
        'game_mode', 
        'is_vs_ai',
        'p1_score', 
        'p2_score', 
        'p1_points_earned', 
        'p2_points_earned'
    ];

    protected $casts = [
        'game_mode' => GameMode::class,
        'is_vs_ai' => 'boolean',
    ];

    public function player1(): BelongsTo { return $this->belongsTo(User::class, 'player_1_id'); }
    public function player2(): BelongsTo { return $this->belongsTo(User::class, 'player_2_id'); }
    public function winner(): BelongsTo { return $this->belongsTo(User::class, 'winner_id'); }
}
