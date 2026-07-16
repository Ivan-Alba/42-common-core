<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerAbandonedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        protected string $matchUuid,
        protected int $player_id
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('match.' . $this->matchUuid)];
    }

    public function broadcastAs(): string
    {
        return 'match.player_abandoned';
    }

    public function broadcastWith(): array
    {
        return [
            'abandoned_player_id' => $this->player_id,
        ];
    }
}
