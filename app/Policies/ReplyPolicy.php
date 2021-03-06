<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy extends  Policy
{
    use HandlesAuthorization;

    // 评论的作者或者是topic的作者都可以删除reply
    public function destroy(User $user,Reply $reply)
    {
        if ($user->can('manage_contents')){
            return true;
        }

        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }

    // update
    public function update(User $user,Reply $reply)
    {
        if ($user->can('manage_contents')){
            return true;
        }

        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
