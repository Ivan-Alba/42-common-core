<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event broadcasted to notify Unity that a move must be forced due to timeout.
 * Matches the ForcePlayCardEvent DTO in Unity.
 */
class ForcePlayCardEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param string $matchUuid
     * @param int $playerId
     * @param int $cardId
     * @param int $boardIndex
     */
    public function __construct(
        public string $matchUuid,
        public int $playerId,
        public int $cardId,
        public int $boardIndex
    ) {
    }

    /**
     * In Unity listener: "match.force_play_card"
     */
    public function broadcastAs(): string
    {
        return 'match.force_play_card';
    }

    /**
     * Payload matching the Unity DTO structure.
     */
    public function broadcastWith(): array
    {
        return [
            'player_id' => (int) $this->playerId,
            'card_id' => (int) $this->cardId,
            'board_index' => (int) $this->boardIndex,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
