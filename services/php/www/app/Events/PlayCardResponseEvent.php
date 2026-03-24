<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event triggered when a player successfully places a card.
 * This is the "Source of Truth" for all clients to update their UI.
 */
class PlayCardResponseEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param string $matchUuid
     * @param array $responseData Data structure matching Unity's PlayCardResponse DTO
     */
    public function __construct(
        public string $matchUuid,
        public array $responseData
    ) {
    }

    /**
     * The event name used by ReverbService in Unity.
     */
    public function broadcastAs(): string
    {
        return 'match.card_played';
    }

    /**
     * Data to be sent to the clients.
     * Ensures types match the C# DTO: int for IDs and double for timestamps.
     */
    public function broadcastWith(): array
    {
        return [
            'player_id' => (int) $this->responseData['player_id'],
            'card_id' => (int) $this->responseData['card_id'],
            'board_index' => (int) $this->responseData['board_index'],
            'animation_steps' => $this->responseData['animation_steps'],
            'match_over' => (bool) $this->responseData['match_over'],
            'server_time_now' => (float) $this->responseData['server_time_now'],
            'next_turn_start_time' => (float) $this->responseData['next_turn_start_time'],
            'turn_end_time' => (float) $this->responseData['turn_end_time'],
        ];
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('match.' . $this->matchUuid),
        ];
    }
}
