<?php

namespace App\Enums;

enum MatchStatus: string
{
    case PENDING = 'pending';     // Waiting for both players to accept
    case LOADING = 'loading';     // Both accepted, waiting for Unity handshake
    case SELECTING = 'selecting'; // Picking cards
    case PLAYING = 'playing';     // Duel in progress
    case FINISHED = 'finished';    // Match ended, showing results
}
