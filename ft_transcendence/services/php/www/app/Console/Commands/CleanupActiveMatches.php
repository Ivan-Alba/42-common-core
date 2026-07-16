<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActiveMatch;
use App\Models\User;
use App\Enums\MatchStatus;
use App\Http\Controllers\MatchmakingController;
use Carbon\Carbon;

class CleanupActiveMatches extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'matches:cleanup-active';

    protected $description = 'Clean up stuck pending matches and inactive game sessions in active_matches table';

    public function handle()
    {
        $this->cleanupPendingMatches();
        $this->cleanupInactiveMatches();

        $this->info("Cleanup process finished.");
    }

    /**
     * Logic for matches that never started (Stuck in PENDING).
     */
    private function cleanupPendingMatches()
    {
        $pendingThreshold = Carbon::now()->subSeconds(20);

        $expiredMatches = ActiveMatch::where('status', MatchStatus::PENDING)
            ->where('created_at', '<', $pendingThreshold)
            ->get();

        foreach ($expiredMatches as $match) {
            $this->warn("Pending Timeout: Match {$match->match_uuid}");

            // Handle P1 not ready
            if (!$match->p1_ready) {
                $user1 = User::find($match->player_1_id);
                if ($user1) {
                    app(MatchmakingController::class)->handleUserDisconnection($user1);
                }
            }

            // Handle P2 not ready
            $stillExists = ActiveMatch::where('id', $match->id)->exists();
            if ($stillExists && !$match->p2_ready) {
                $user2 = User::find($match->player_2_id);
                if ($user2) {
                    app(MatchmakingController::class)->handleUserDisconnection($user2);
                }
            }
        }
    }

    /**
     * Logic for active matches with no activity (updated_at > 3 minutes).
     * This catches crashes, double-tab closures, and abandoned early phases.
     */
    private function cleanupInactiveMatches()
    {
        $inactivityThreshold = Carbon::now()->subMinutes(2);

        $deadMatches = ActiveMatch::where('updated_at', '<', $inactivityThreshold)
            ->get();

        foreach ($deadMatches as $match) {
            $this->error("Inactivity Cleanup: Removing dead match {$match->match_uuid}");

            $match->delete();
        }
    }
}
