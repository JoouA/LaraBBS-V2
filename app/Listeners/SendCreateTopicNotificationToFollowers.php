<?php

namespace App\Listeners;

use App\Events\CreateTopic;
use App\Notifications\TopicToFollowersNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCreateTopicNotificationToFollowers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreateTopic  $event
     * @return void
     */
    public function handle(CreateTopic $event)
    {
        $followers = $event->user->followers()->get();

        $topic = $event->topic;


        if (count($followers)){
            foreach ($followers as $follower){
                $follower->notify(new TopicToFollowersNotification($topic));
            }
        }

    }
}
