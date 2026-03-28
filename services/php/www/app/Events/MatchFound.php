<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchFound implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param int $userId El ID del usuario que estaba esperando (Jugador A).
     * @param string $matchUuid El UUID de la partida en estado 'pending'.
     */
    public function __construct(
        public int $userId,
        public string $matchUuid
    ) {
    }

    public function broadcastOn(): array
    {
        // Usamos el canal privado que ya tienes para el ping/pong
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'match.found';
    }

    /**
     * Datos que React recibirá
     */
    public function broadcastWith(): array
    {
        return [
            'match_uuid' => $this->matchUuid,
            'expires_at' => now()->addSeconds(15)->timestamp, // Opcional: para un countdown visual
        ];
    }
}
