<?php

namespace App\Policies;

use App\User;
use App\Fish;
use Illuminate\Auth\Access\HandlesAuthorization;

class FishPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->can('access-fish');
    }

    /**
     * Determine whether the user can view all the fish.
     *
     * @param  \App\User  $user
     * @param  \App\Fish  $fish
     * @return mixed
     */
    public function view(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create fishs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the fish.
     *
     * @param  \App\User  $user
     * @param  \App\Fish  $fish
     * @return mixed
     */
    public function show(User $user, Fish $fish)
    {
        return $fish->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the fish.
     *
     * @param  \App\User  $user
     * @param  \App\Fish  $fish
     * @return mixed
     */
    public function update(User $user, Fish $fish)
    {
        return $fish->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the fish.
     *
     * @param  \App\User  $user
     * @param  \App\Fish  $fish
     * @return mixed
     */
    public function delete(User $user, Fish $fish)
    {
        return $fish->user_id === $user->id;
    }
}
