<?php

namespace App\Events;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchFound implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected int $userId;
    protected User $opponent;
    protected string $matchUuid;

    /**
     * Using public properties here but ensuring we control the output
     * via broadcastWith(). 
     */
    public function __construct(int $userId, User $opponent, string $matchUuid)
    {
        $this->userId = $userId;
        $this->opponent = $opponent;
        $this->matchUuid = $matchUuid;
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

    /**
     * Custom event name for Echo.
     */
    public function broadcastAs(): string
    {
        return 'match.found';
    }

    /**
     * Data received by React.
     */
    public function broadcastWith(): array
    {
        // Ensure stats are loaded for the Resource
        $this->opponent->loadMissing('stats');

        return [
            'match_uuid' => $this->matchUuid,
            'opponent' => (new UserResource($this->opponent))->resolve(),
            'expires_at' => now()->addSeconds(15)->timestamp,
        ];
    }
}
