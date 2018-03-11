<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateFollow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $follower,$following;

    /**
     * CreateFollow constructor.
     * @param User $follower
     * @param User $following
     */
    public function __construct(User $follower,User $following)
    {
        $this->follower = $follower;
        $this->following = $following;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
