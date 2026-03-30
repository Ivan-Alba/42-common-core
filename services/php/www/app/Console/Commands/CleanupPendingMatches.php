<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActiveMatch;
use App\Models\User;
use App\Enums\MatchStatus;
use App\Http\Controllers\MatchmakingController;
use Carbon\Carbon;

class CleanupPendingMatches extends Command
{
    /**
     * The name and signature of the console command.
     * English: Command to remove matches that were not confirmed in time.
     */
    protected $signature = 'matchmaking:cleanup-pending';

    protected $description = 'Clean up matches that stayed in PENDING status for too long';

    public function handle()
    {
        $timeoutThreshold = Carbon::now()->subSeconds(20);

        // Buscamos partidas que lleven más de 20 segundos en PENDING
        $expiredMatches = ActiveMatch::where('status', MatchStatus::PENDING)
            ->where('created_at', '<', $timeoutThreshold)
            ->get();

        foreach ($expiredMatches as $match) {
            $this->info("Timeout detected for match: {$match->match_uuid}");

            // 1. ¿El Jugador 1 no está listo? Lo desconectamos.
            // Esto automáticamente avisará al P2 y lo re-encolará si él sí estaba listo.
            if (!$match->p1_ready) {
                $user1 = User::find($match->player_1_id);
                if ($user1) {
                    app(MatchmakingController::class)->handleUserDisconnection($user1);
                    $this->info("P1 ({$user1->id}) was not ready. Cleanup triggered.");
                }
            }

            // 2. ¿El Jugador 2 no está listo? Lo desconectamos.
            // Si el P1 ya disparó la limpieza arriba, esta partida ya no existirá, 
            // pero el check de p2_ready es por si AMBOS fallaron o solo el P2.
            $stillExists = ActiveMatch::where('id', $match->id)->exists();

            if ($stillExists && !$match->p2_ready) {
                $user2 = User::find($match->player_2_id);
                if ($user2) {
                    app(MatchmakingController::class)->handleUserDisconnection($user2);
                    $this->info("P2 ({$user2->id}) was not ready. Cleanup triggered.");
                }
            }
        }
    }
}
