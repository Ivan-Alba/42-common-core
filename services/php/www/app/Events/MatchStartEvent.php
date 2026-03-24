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
        public int $firstPlayerId,
        public float $serverTimeNow,
        public float $turnStartTime,
        public float $turnEndTime
    ) {
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'match.start';
    }

    /**
     * Data payload for the event.
     * We explicitly cast timestamps to (float) for C# double precision compatibility.
     */
    public function broadcastWith(): array
    {
        return [
            'first_player_id' => (int) $this->firstPlayerId,
            'server_time_now' => (float) $this->serverTimeNow,
            'turn_start_time' => (float) $this->turnStartTime,
            'turn_end_time' => (float) $this->turnEndTime,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
