<?php

namespace App\Jobs;

use App\Models\ActiveMatch;
use App\Events\ForceDeckSelectionEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ForceSelectionTimeout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $matchUuid)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $match = ActiveMatch::where('match_uuid', $this->matchUuid)->first();

        // If match doesn't exist or is no longer in selection phase, do nothing
        if (!$match || $match->status !== 'selecting') {
            return;
        }

        // If anyone is still not ready, we broadcast the force event
        if (!$match->p1_ready || !$match->p2_ready) {
            // Broadcast the Force event (Event 3)
            broadcast(new ForceDeckSelectionEvent($this->matchUuid, 'selection_timeout'));
        }
    }
}
