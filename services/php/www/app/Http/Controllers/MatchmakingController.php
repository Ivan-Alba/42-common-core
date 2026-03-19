<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MatchmakingService;
use App\Enums\GameMode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatchmakingController extends Controller
{
    public function __construct(protected MatchmakingService $matchmaking) {}

    /**
     * Endpoint: POST /api/matchmaking/join
     * Request: { "game_mode": "CAMPAIGN_1" }
     */
    public function join(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode' => 'required|string'
        ]);

        try {
            $mode = GameMode::from($request->game_mode);
            $user = $request->user();

            $match = $this->matchmaking->findOrCreateMatch($user, $mode);

            return response()->json([
                'success' => true,
                'match_uuid' => $match->match_uuid
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
