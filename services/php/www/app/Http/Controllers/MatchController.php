<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Http\Resources\MatchInitialResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatchController extends Controller
{
    public function getMatchData(Request $request, $matchId): JsonResponse
    {
        // --- DEBUG MODE BYPASS ---
        if ($matchId === 'debug') {
            return $this->getDebugMatchResponse();
        }
        // -------------------------

        $match = Matches::with([
            'player1.cards', 
            'player2.cards'
        ])->findOrFail($matchId);

        // This will fail if no token is sent, so debug mode is vital here
        $authUserId = $request->user()->id; 

        if ($authUserId != $match->player_1_id && $authUserId != $match->player_2_id) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized: You are not a participant in this match.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new MatchInitialResource($match, $authUserId) 
        ]);
    }

    /**
     * Returns a hardcoded JSON response for development purposes.
     */
    private function getDebugMatchResponse(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'match_id' => 'debug_001',
                'server_timestamp' => now()->timestamp,
                'language' => 'es',
                'config' => [
                    'board_size' => 3,
                    'hand_size' => 5,
                    'turn_time_limit' => 30,
                    'selection_time_limit' => 45,
                    'max_deck_cost' => 6,
                    'rules' => ['open', 'same', 'plus', 'combo'],
                    'first_player_id' => 'player_local',
                ],
                'local_player' => [
                    'id' => 'player_local',
                    'name' => 'Unity Developer',
                    'avatar_url' => 'https://avatarfiles.alphacoders.com/103/thumb-1920-103373.png',
                    'is_ai' => false,
                    'collection_ids' => [ "card_000", "card_001", "card_002", "card_003", "card_004", "card_005", "card_006", "card_007", "card_008","card_009",
            "card_010", "card_011", "card_012", "card_013", "card_014", "card_015", "card_016", "card_017", "card_018", "card_019", "card_020", "card_021", "card_022", "card_023"]
                ],
                'opponent' => [
                    'id' => 'ai_bot_01',
                    'name' => 'Backend Bot',
                    'avatar_url' => 'https://pbs.twimg.com/profile_images/1202938752387170304/Z0TlRM6d_400x400.jpg',
                    'is_ai' => true,
                    'collection_ids' => ["card_000", "card_001", "card_002", "card_003", "card_004", "card_005", "card_006", "card_007", "card_008", "card_009"]
                ]
            ]
        ]);
    }
}
