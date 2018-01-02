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
        return $user->isAuthorOf($topic);
    }

    /**
     * @param User $user
     * @param Topic $topic
     * @return bool
     */
    public function edit(User $user,Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }

    /**
     * @param User $user
     * @param Topic $topic
     * @return bool
     */
    public function destroy(User $user,Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }
}
