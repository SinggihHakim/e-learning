<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    /**
     * Menentukan apakah pengguna berhak mengubah materi ini.
     */
    public function update(User $user, Material $material): bool
    {
        return $material->course->teacher_id === $user->id;
    }

    /**
     * Menentukan apakah pengguna berhak menghapus materi.
     */
    public function delete(User $user, Material $material): bool
    {
        return $material->course->teacher_id === $user->id;
    }

    /**
     * Menentukan apakah pengguna diizinkan untuk melihat detail materi.
     */
    public function view(User $user, Material $material): bool
    {
        // Teacher of the course, or enrolled students
        return $material->course->teacher_id === $user->id 
            || clone $material->course->students()->where('user_id', $user->id)->exists();
    }
}
