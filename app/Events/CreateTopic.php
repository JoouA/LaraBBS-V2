<?php

namespace App\Events;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateTopic
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $topic;
    public $user;

    /**
     * Create a new event instance.
     * CreateTopic constructor.
     * @param User $user
     * @param Topic $topic
     */
    public function __construct(User $user,Topic $topic)
    {
        $this->user = $user;
        $this->topic = $topic;
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
