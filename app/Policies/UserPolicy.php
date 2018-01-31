<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(User $currentUser,User $user)
    {
        if ($currentUser->can('manage_users')){
            return true;
        }

        return $currentUser->id === $user->id;
    }

    public function update(User $currentUser,User $user)
    {
        if ($currentUser->can('manage_users')){
            return true;
        }

        return $currentUser->id === $user->id;
    }
}
