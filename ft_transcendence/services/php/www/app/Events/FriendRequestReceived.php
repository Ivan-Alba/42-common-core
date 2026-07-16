<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Usamos ShouldBroadcastNow para que se envíe al instante sin necesidad de configurar colas de trabajos
class FriendRequestReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiverId;
    public $sender;

    /**
     * Create a new event instance.
     */
    public function __construct($receiverId, User $sender)
    {
        $this->receiverId = $receiverId;
        $this->sender = $sender;
    }

    /**
     * Get the channels the event should broadcast on.
     * ¡Este es el canal exacto que vimos en routes/channels.php!
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->receiverId),
        ];
    }

    /**
     * The event's broadcast name.
     * Para asegurarnos de que React escuche exactamente este nombre.
     */
    public function broadcastAs(): string
    {
        return 'FriendRequestReceived';
    }

    /**
     * Get the data to broadcast.
     * Esto es lo que va a recibir tu console.log en React.
     */
    public function broadcastWith(): array
    {
        return [
            'requester_id' => $this->sender->id,
            'username' => $this->sender->name, 
            'avatar' => $this->sender->avatar,
        ];
    }
}