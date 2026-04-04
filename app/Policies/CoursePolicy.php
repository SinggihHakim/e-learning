<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Menentukan apakah pengguna diizinkan untuk melihat kelas.
     */
    public function view(User $user, Course $course): bool
    {
        // Guru pengajar ATAU Siswa yang terdaftar
        return $user->id === $course->teacher_id
            || $course->students()->where('users.id', $user->id)->exists();
    }

    /**
     * Menentukan apakah pengguna berhak mengubah data kelas.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->teacher_id;
    }

    /**
     * Menentukan apakah pengguna berhak menghapus kelas.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->id === $course->teacher_id;
    }
}
