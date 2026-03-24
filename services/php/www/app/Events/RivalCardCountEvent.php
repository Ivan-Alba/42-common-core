<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RivalCardCountEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $matchUuid,
        public int $player_id, 
        public int $current_count
    ) {}

    /**
     * Unity listener: "match.rival_count"
     */
    public function broadcastAs(): string
    {
        return 'match.rival_count';
    }

    public function broadcastWith(): array
    {
        return [
            'player_id' => (int) $this->player_id,
            'current_count' => (int) $this->current_count,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
