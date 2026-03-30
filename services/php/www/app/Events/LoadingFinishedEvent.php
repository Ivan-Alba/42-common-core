<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoadingFinishedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param string $matchUuid UUID of the match
     * @param float $serverTimestamp Current server time for clock sync
     * @param float $selectionEndTime When the selection phase MUST end
     */
    public function __construct(
        public string $matchUuid,
        public float $serverTimestamp,
        public float $selectionEndTime
    ) {
    }

    /**
     * The event's broadcast name in Unity.
     */
    public function broadcastAs(): string
    {
        return 'loading.finished';
    }

    /**
     * Data payload for Unity.
     * We include the selection end time so both Unity clients start 
     * the countdown at the exact same moment.
     */
    public function broadcastWith(): array
    {
        return [
            'server_timestamp' => (float) $this->serverTimestamp,
            'selection_end_time' => (float) $this->selectionEndTime,
        ];
    }

    /**
     * Broadcast on the specific match channel.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
