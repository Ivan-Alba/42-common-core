<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStartEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $matchUuid,
        public string $firstPlayerId,
        public float $serverTimeNow,
        public float $turnStartTime
    ) {}

    /**
     * The event's broadcast name.
     * In Unity, you will listen for "match.start".
     */
    public function broadcastAs(): string
    {
        return 'match.start';
    }

    /**
     * Data payload for the event.
     */
    public function broadcastWith(): array
    {
        return [
            'first_player_id' => $this->firstPlayerId,
            'server_time_now' => $this->serverTimeNow,
            'turn_start_time' => $this->turnStartTime,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
