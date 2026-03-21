<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForcePlayCardEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $matchUuid,
        public string $playerId,
        public string $reason = 'turn_timeout'
    ) {}

    /**
     * The event's broadcast name.
     * In Unity: "match.force_play_card"
     */
    public function broadcastAs(): string
    {
        return 'match.force_play_card';
    }

    public function broadcastWith(): array
    {
        return [
            'player_id' => $this->playerId,
            'reason'    => $this->reason,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
