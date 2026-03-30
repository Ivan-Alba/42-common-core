<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RivalReadyEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $matchUuid,
        public int $rivalId,
        public array $selectedCardIds
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
            'selected_card_ids' => $this->selectedCardIds,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
