<?php

namespace App\Services;

use App\Models\Course;

class CourseService
{
    /**
     * Menyimpan data kelas baru ke basis data.
     *
     * @param array $data Data kelas yang tervalidasi
     * @param int $teacherId ID guru pembuat kelas
     * @return Course
     */
    public function createCourse(array $data, int $teacherId): Course
    {
        return Course::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'teacher_id' => $teacherId,
        ]);
    }

    /**
     * Memperbarui rincian kelas yang ada.
     *
     * @param Course $course Kelas yang diubah
     * @param array $data Data kelas yang tervalidasi
     * @return Course
     */
    public function updateCourse(Course $course, array $data): Course
    {
        $course->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);
        
        return $course;
    }

    /**
     * Mengeluarkan siswa tertentu dari sebuah kelas.
     *
     * @param Course $course Kelas sasaran
     * @param int $studentId Pengenal (ID) siswa yang akan dikeluarkan
     * @return void
     */
    public function removeStudent(Course $course, int $studentId): void
    {
        $course->students()->detach($studentId);
    }
}
