<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    /**
     * Menentukan apakah pengguna dapat melihat daftar presensi.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['teacher', 'student']);
    }

    /**
     * Menentukan apakah pengguna diizinkan untuk melihat detail sesi presensi.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->role === 'teacher') {
            return $user->id === $attendance->course->teacher_id;
        }

        if ($user->role === 'student') {
            return $attendance->course->students()->where('users.id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Menentukan apakah pengguna diizinkan untuk membuat sesi presensi.
     */
    public function create(User $user): bool
    {
        return $user->role === 'teacher';
    }

    /**
     * Menentukan apakah pengguna diizinkan untuk memperbarui sesi presensi.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        return $user->id === $attendance->course->teacher_id;
    }

    /**
     * Menentukan apakah pengguna diizinkan untuk menghapus sesi presensi.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->id === $attendance->course->teacher_id;
    }
}
