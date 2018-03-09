<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\ReplyMentionNotification;
use App\Notifications\ReplyNotification;
use App\Models\Traits\ReplyMention;

class ReplyObserver
{
    use ReplyMention;

    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content,'user_topic_body');
    }

    // creating, created, updating, updated, saving,
    // saved,  deleting, deleted, restoring, restored
    public function created(Reply $reply)
    {
        $topic = $reply->topic;

        $topic->increment('reply_count',1);

        // 如果评论的作者不是话题的作者，才需要通知

        if ( ! $reply->user->isAuthorOf($topic) ){
             $topic->user->notify(new ReplyNotification($reply));
        }

        // 作者@某一个人，要对哪一个人进行通知,获取到@的人的用户信息
        $mention_users = $this->getMentionUser($reply->content);

        if (count($mention_users) > 0){
            foreach ($mention_users as $mention_user){
                $mention_user->notify(new  ReplyMentionNotification($reply));
            }
        }

    }

    public function saving(Reply $reply)
    {
        $reply->content = clean($reply->content,'user_topic_body');
    }


    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count',1);
    }
}