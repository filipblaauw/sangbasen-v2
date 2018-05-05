<?php

namespace App\Policies;

use App\User;
use App\Song;
use Illuminate\Auth\Access\HandlesAuthorization;

class SongPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->can('access-song');
    }

    /**
     * Determine whether the user can view all the song.
     *
     * @param  \App\User  $user
     * @param  \App\Song  $song
     * @return mixed
     */
    public function view(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create songs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the song.
     *
     * @param  \App\User  $user
     * @param  \App\Song  $song
     * @return mixed
     */
    public function show(User $user, Song $song)
    {
        //return $song->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the song.
     *
     * @param  \App\User  $user
     * @param  \App\Song  $song
     * @return mixed
     */
    public function update(User $user, Song $song)
    {
        return $song->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the song.
     *
     * @param  \App\User  $user
     * @param  \App\Song  $song
     * @return mixed
     */
    public function delete(User $user, Song $song)
    {
        return $song->user_id === $user->id;
    }
}
