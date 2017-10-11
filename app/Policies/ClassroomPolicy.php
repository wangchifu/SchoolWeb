<?php

namespace App\Policies;

use App\User;
use App\Classroom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the classroom.
     *
     * @param  \App\User  $user
     * @param  \App\Classroom  $classroom
     * @return mixed
     */
    public function view(User $user, Classroom $classroom)
    {
        return $user->group_id > "0";
    }

    /**
     * Determine whether the user can create classrooms.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->group_id === 1 or $user->group_id2 === 1;
    }

    /**
     * Determine whether the user can update the classroom.
     *
     * @param  \App\User  $user
     * @param  \App\Classroom  $classroom
     * @return mixed
     */
    public function update(User $user, Classroom $classroom)
    {
        //
    }

    /**
     * Determine whether the user can delete the classroom.
     *
     * @param  \App\User  $user
     * @param  \App\Classroom  $classroom
     * @return mixed
     */
    public function delete(User $user, Classroom $classroom)
    {
        //
    }
}
