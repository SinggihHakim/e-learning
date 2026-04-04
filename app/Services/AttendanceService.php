<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class AttendanceService
{
    /**
     * Membuat sesi presensi baru.
     */
    public function createAttendance(array $data): Attendance
    {
        return Attendance::create($data);
    }

    /**
     * Memperbarui sesi presensi yang sudah ada.
     */
    public function updateAttendance(Attendance $attendance, array $data): bool
    {
        return $attendance->update($data);
    }

    /**
     * Menghapus sesi presensi.
     */
    public function deleteAttendance(Attendance $attendance): bool
    {
        return $attendance->delete();
    }

    /**
     * Mencatat kehadiran siswa ke dalam sesi presensi.
     * Secara otomatis menghitung status kehadiran berdasarkan waktu atau alasan izin.
     * Mengembalikan pesan sukses atau melempar Exception jika gagal validasi lokal.
     *
     * @throws Exception
     */
    public function submitStudentAttendance(Attendance $attendance, User $student, ?string $password, ?string $reason): string
    {
        // 1. Cek duplikasi
        if (AttendanceStudent::where('attendance_id', $attendance->id)->where('student_id', $student->id)->exists()) {
            throw new Exception(__('Anda sudah mengisi presensi untuk sesi ini.'));
        }

        // 2. Cek kecocokan password jika disetel oleh guru
        if (!empty($attendance->password) && $attendance->password !== $password) {
            throw new Exception(__('Kata sandi presensi salah.'));
        }

        // 3. Tentukan status otomatis
        $now = now();
        $endTime = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->end_time);
        $startTime = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->start_time);

        if (!empty($reason)) {
            $status = 'izin';
        } elseif ($now->greaterThan($endTime)) {
            $status = 'tidak_hadir';
        } elseif ($now->greaterThan($startTime->copy()->addMinutes(15))) {
            // Toleransi telat 15 menit
            $status = 'telat';
        } else {
            $status = 'hadir';
        }

        // 4. Catat di database
        AttendanceStudent::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => $status,
        ]);

        $statusMessages = [
            'hadir' => __('Presensi berhasil dicatat: Hadir.'),
            'telat' => __('Presensi berhasil dicatat: Terlambat.'),
            'izin' => __('Presensi berhasil dicatat: Izin.'),
            'tidak_hadir' => __('Presensi berhasil dicatat: Tidak Hadir.'),
        ];

        return $statusMessages[$status] ?? __('Presensi berhasil dicatat.');
    }
}
