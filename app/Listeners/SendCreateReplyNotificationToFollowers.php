<?php

namespace App\Listeners;

use App\Events\CreateReply;
use App\Notifications\ReplyToFollowersNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCreateReplyNotificationToFollowers
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
     * @param  CreateReply  $event
     * @return void
     */
    public function handle(CreateReply $event)
    {
        $user = $event->user;
        $reply = $event->reply;

        $followers = $user->followers()->get();

        if (count($followers)){
            foreach ($followers as $follower){
                $follower->notify(new ReplyToFollowersNotification($user,$reply));
            }
        }
    }
}
