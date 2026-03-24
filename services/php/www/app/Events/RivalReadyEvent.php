<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RivalReadyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $matchUuid,
        public int $rivalId
    ) {}

    /**
     * The event's broadcast name.
     * In Unity, you will listen for "match.rival_ready".
     */
    public function broadcastAs(): string
    {
        return 'match.rival_ready';
    }

    /**
     * The data that will be sent in the JSON payload.
     */
    public function broadcastWith(): array
    {
        return [
            'rival_id' => (int) $this->rivalId,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
