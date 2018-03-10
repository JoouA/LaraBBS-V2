<?php

namespace App\Listeners;

use App\Events\CreateVote;
use App\Notifications\VoteNotification;
use App\Notifications\VoteToFollowersNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCreateVoteNotificationToFollowers
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
     * @param  CreateVote  $event
     * @return void
     */
    public function handle(CreateVote $event)
    {
        $followers = $event->user->followers()->get();
        $topic = $event->topic;

        //给关注的用户发送通知
        if (count($followers)){
            foreach ($followers as $follower){
                $follower->notify(new VoteToFollowersNotification($event->user,$topic));
            }
        }
    }
}
