<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForceDeckSelectionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $matchUuid,
        public string $reason = 'selection_timeout'
    ) {}

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
