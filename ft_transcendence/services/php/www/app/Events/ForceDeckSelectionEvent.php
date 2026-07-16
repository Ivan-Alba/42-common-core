<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForceDeckSelectionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $matchUuid,
        public string $reason = 'selection_timeout'
    ) {}

    /**
     * The event's broadcast name.
     * This decouples the PHP class name from the WebSocket event name.
     */
    public function broadcastAs(): string
    {
        return 'match.force_selection';
    }

    public function broadcastWith(): array
    {
        return [
            'reason' => $this->reason,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
