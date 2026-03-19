<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ActiveMatch;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// --- EXISTING PING CHANNELS ---

Broadcast::channel('reverb-ping-public', function () {
    return true;
}, ['guards' => ['sanctum']]);

Broadcast::channel('reverb-ping-private', function ($user) {
    return true;
}, ['guards' => ['sanctum']]);

Broadcast::channel('reverb-ping-presence', function ($user) {
    return ['user-id' => $user?->id];
}, ['guards' => ['sanctum']]);


// --- GAME SESSION PRIVATE CHANNEL ---

/**
 * Channel for real-time communication during an active match.
 * Unity clients must subscribe to: private-match.{match_uuid}
 */
Broadcast::channel('match.{matchUuid}', function ($user, $matchUuid) {
    // 1. Fetch the active match session using the UUID
    $match = ActiveMatch::where('match_uuid', $matchUuid)->first();
    
    if (!$match) {
        return false;
    }

    // 2. Authorization Logic:
    // Only allow the two participants defined in the database to subscribe to this channel.
    // We cast to (int) to ensure strict ID comparison.
    return (int) $user->id === (int) $match->player_1_id || 
           (int) $user->id === (int) $match->player_2_id;

}, ['guards' => ['sanctum']]); // CRITICAL: Ensure Sanctum guard is used for API token validation
