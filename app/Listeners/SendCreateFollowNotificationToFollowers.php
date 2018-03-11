<?php

namespace App\Listeners;

use App\Events\CreateFollow;
use App\Notifications\FollowToFollowersNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCreateFollowNotificationToFollowers
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
     * @param  CreateFollow  $event
     * @return void
     */
    public function handle(CreateFollow $event)
    {
        $user = $event->follower;
        $following = $event->following;

        $followers = $user->followers()->get();

        if (count($followers)){
            foreach ($followers as $follower){
                $follower->notify(new FollowToFollowersNotification($user,$following));
            }
        }
    }
}
