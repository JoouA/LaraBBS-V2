<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @param User $user
     * @param Topic $topic
     * @return bool
     */
    public function update(User $user,Topic $topic)
    {
        return $user->id == $topic->user_id;
    }

    /**
     * @param User $user
     * @param Topic $topic
     * @return bool
     */
    public function edit(User $user,Topic $topic)
    {
        return $user->id == $topic->user_id;
    }
}
