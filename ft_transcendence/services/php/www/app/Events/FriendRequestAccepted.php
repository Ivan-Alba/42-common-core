<?php
// app/Events/FriendRequestAccepted.php
namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestAccepted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiver;
    public $requesterId;

    public function __construct(User $receiver, $requesterId)
    {
        $this->receiver = $receiver;
        $this->requesterId = $requesterId;
    }

    public function broadcastOn(): array
    {
		// Send the event to the private channel of the original requester
        return [new PrivateChannel('user.' . $this->requesterId)];
    }

    public function broadcastAs(): string
    {
        return 'FriendRequestAccepted';
    }
}