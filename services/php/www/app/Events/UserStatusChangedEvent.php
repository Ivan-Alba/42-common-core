<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserStatusChangedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $newStatus; // online, offline, playing, afk

    public function __construct($userId, $newStatus)
    {
        $this->userId = $userId;
        $this->newStatus = $newStatus;
    }

    public function broadcastOn(): array
    {
        $channels = [];
        try {
            $user = User::find($this->userId);
            if (!$user) return [];

			// Obtain both sides of the friendship (those I invited and those who invited me)
            $friendsOfMine = $user->friendsOfMine;
            $friendOf = $user->friendOf;

			// Merge the two collections into a single list of friends
            $allFriends = $friendsOfMine->merge($friendOf);

			// Throw advice to all channels
            if ($allFriends->isNotEmpty()) {
                foreach ($allFriends as $friend) {
                    $channels[] = new PrivateChannel('user.' . $friend->id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in UserStatusChanged: ' . $e->getMessage());
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'UserStatusChanged';
    }
}