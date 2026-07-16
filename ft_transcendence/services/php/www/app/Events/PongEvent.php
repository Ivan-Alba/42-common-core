<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Lightweight event used for RTT (Round-Trip Time) measurement
 * and high-precision clock synchronization between Unity and Laravel.
 */
class PongEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param string $matchUuid Target private channel identifier.
     * @param float $clientTimestamp The original timestamp sent by Unity (Time.realtimeSinceStartup).
     * @param float $serverTime The current authoritative server time (microtime).
     */
    public function __construct(
        public int $userId,
        public float $clientTimestamp,
        public float $serverTime
    ) {
    }

    /**
     * The event's broadcast name used by Reverb/Pusher.
     */
    public function broadcastAs(): string
    {
        return 'match.pong';
    }

    /**
     * Data payload for the event.
     * We cast to (float) to ensure JSON numeric types for C# double precision.
     */
    public function broadcastWith(): array
    {
        return [
            'client_timestamp' => (float) $this->clientTimestamp,
            'server_time_now' => (float) $this->serverTime,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }
}
