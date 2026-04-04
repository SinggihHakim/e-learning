<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AdminUserService
{
    /**
     * Membuat pengguna baru
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }

    /**
     * Memperbarui detail pengguna
     */
    public function updateUser(User $user, array $data): bool
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        return $user->update($updateData);
    }

    /**
     * Menghapus pengguna dengan berbagai protokol keamanan
     *
     * @throws Exception
     */
    public function deleteUser(User $user, int $currentUserId): bool
    {
        // 1. Proteksi Diri Sendiri
        if ($user->id === $currentUserId) {
            throw new Exception(__('Anda tidak dapat menghapus akun Anda sendiri.'));
        }

        // 2. Proteksi Guru: Jangan hapus jika sedang mengajar mata pelajaran
        if ($user->role === 'teacher') {
            if ($user->courses()->exists()) {
                throw new Exception(__('Guru ini tidak dapat dihapus karena masih terdaftar sebagai pengajar di satu atau lebih mata pelajaran. Harap pindahkan mata pelajaran tersebut terlebih dahulu.'));
            }
        }

        // 3. Proteksi Siswa: Jangan hapus jika sudah memiliki jejak akademis (nilai / tugas)
        if ($user->role === 'student') {
            $hasGrades = $user->grades()->exists();
            $hasSubmissions = $user->submissions()->exists();
            $hasQuizAttempts = \App\Models\QuizAttempt::where('student_id', $user->id)->exists();

            if ($hasGrades || $hasSubmissions || $hasQuizAttempts) {
                throw new Exception(__('Siswa ini tidak dapat dihapus karena sudah memiliki rekam jejak akademis (nilai, tugas, atau kuis).'));
            }
        }

        return $user->delete();
    }
}
