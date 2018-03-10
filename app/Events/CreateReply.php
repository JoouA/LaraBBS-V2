<?php

namespace App\Events;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateReply
{
    public $reply;
    public $user;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * CreateReply constructor.
     * @param Reply $reply
     * @param User $user
     */
    public function __construct(User $user,Reply $reply)
    {
        $this->reply = $reply;
        $this->user = $user;
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
