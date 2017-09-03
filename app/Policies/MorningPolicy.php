<?php

namespace App\Policies;

use App\Report;
use App\User;
use App\Morning;
use Illuminate\Auth\Access\HandlesAuthorization;

class MorningPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the morning.
     *
     * @param  \App\User  $user
     * @param  \App\Morning  $morning
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->group_id > "0";
    }

    /**
     * Determine whether the user can create mornings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->group_id === "1";
    }

    /**
     * Determine whether the user can update the morning.
     *
     * @param  \App\User  $user
     * @param  \App\Morning  $morning
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->admin === "1";
    }

    /**
     * Determine whether the user can delete the morning.
     *
     * @param  \App\User  $user
     * @param  \App\Morning  $morning
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->admin === "1";
    }

}
