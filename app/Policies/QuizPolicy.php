<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    /**
     * Menentukan apakah pengguna diizinkan untuk melihat detail kuis.
     */
    public function view(User $user, Quiz $quiz): bool
    {
        return $quiz->course->teacher_id === $user->id 
            || clone $quiz->course->students()->where('user_id', $user->id)->exists();
    }

    /**
     * Menentukan apakah pengguna berhak mengubah rincian kuis.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        return $quiz->course->teacher_id === $user->id;
    }

    /**
     * Menentukan apakah pengguna berhak menghapus kuis.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        return $quiz->course->teacher_id === $user->id;
    }

    /**
     * Menentukan apakah pengguna berhak mengelola kuis (menambah soal, menilai tugas).
     */
    public function manage(User $user, Quiz $quiz): bool
    {
        return $quiz->course->teacher_id === $user->id;
    }
}
