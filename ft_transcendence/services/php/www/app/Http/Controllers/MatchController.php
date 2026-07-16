<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\ActiveMatch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    /**
     * Finalizes an active match and moves its data to the historical record.
     * This is an internal logic called when the MatchEngine detects the game is over.
     */
    public function finalize(string $matchUuid, array $results): Matches
    {
        return DB::transaction(function () use ($matchUuid, $results) {
            // 1. Get the session state before deleting it
            $active = ActiveMatch::where('match_uuid', $matchUuid)->firstOrFail();

            // 2. Create the permanent record in 'matches' table
            $matchHistory = Matches::create([
                'player_1_id'      => $active->player_1_id,
                'player_2_id'      => $active->player_2_id,
                'winner_id'        => $results['winner_id'],
                'game_mode'        => $active->game_mode,
                'is_vs_ai'         => $results['is_vs_ai'],
                'p1_score'         => $results['p1_score'],
                'p2_score'         => $results['p2_score'],
                'p1_points_earned' => $results['p1_points'] ?? 0,
                'p2_points_earned' => $results['p2_points'] ?? 0,
            ]);

            // 3. Cleanup the operational table
            $active->delete();

            return $matchHistory;
        });
    }

    /**
     * Optional: Endpoint to force-close a match if needed from an admin panel or debug tool.
     */
    public function forceClose(Request $request, string $matchUuid): JsonResponse
    {
        // Internal logic to handle manual closures
        return response()->json(['message' => 'Match closed manually.']);
    }
}
