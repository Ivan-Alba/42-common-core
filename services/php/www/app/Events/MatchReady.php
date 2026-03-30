<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchReady implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param int $userId El ID del usuario que debe recibir la notificación.
     * @param string $matchUuid
     */
    public function __construct(
        public int $userId,
        public string $matchUuid
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'match.ready';
    }

    public function broadcastWith(): array
    {
        return [
            'match_uuid' => $this->matchUuid,
            'status' => 'loading'
        ];
    }
}
