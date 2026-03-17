<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matches extends Model
{
    use HasFactory;

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
        'p2_points_earned',
    ];

    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_1_id');
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_2_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
