<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    /**
     * Determine whether the user can view the assignment.
     */
    public function view(User $user, Assignment $assignment): bool
    {
        return $assignment->course->teacher_id === $user->id 
            || clone $assignment->course->students()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can modify the assignment.
     */
    public function update(User $user, Assignment $assignment): bool
    {
        return $assignment->course->teacher_id === $user->id;
    }

    /**
     * Determine whether the user can delete the assignment.
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        return $assignment->course->teacher_id === $user->id;
    }

    /**
     * Determine whether the user can grade the assignment submissions.
     */
    public function grade(User $user, Assignment $assignment): bool
    {
        return $assignment->course->teacher_id === $user->id;
    }
}
