<?php

namespace App\Services\Student;

use App\Models\Course;
use App\Models\User;

class CourseService
{
    /**
     * Mendaftarkan siswa ke sebuah kelas jika belum terdaftar.
     * Mengembalikan true jika sukses terdaftar, false jika sudah ada.
     *
     * @param User $student
     * @param Course $course
     * @return bool
     */
    public function enrollStudent(User $student, Course $course): bool
    {
        if (!$student->enrolledCourses()->where('courses.id', $course->id)->exists()) {
            $student->enrolledCourses()->attach($course->id);
            return true;
        }
        
        return false;
    }

    /**
     * Mengambil ikhtisar lengkap progres belajar siswa untuk sebuah kelas.
     * Menggabungkan Material, Assignment, Quiz, dan Attendance ke dalam satu payload efisien.
     *
     * @param User $student
     * @param Course $course
     * @return array Payload data pendukung (view template variables)
     */
    public function getStudentCourseOverview(User $student, Course $course): array
    {
        // 1. Eager load base course relationships to prevent N+1 Queries
        $course->load(['teacher', 'materials', 'assignments', 'quizzes.questions', 'attendances']);

        $studentId = $student->id;

        // 2. Fetch all student progress for materials in ONE query
        $completedMaterials = \App\Models\Progress::where('student_id', $studentId)
            ->whereIn('material_id', $course->materials->pluck('id'))
            ->where('completed', true)
            ->pluck('material_id')
            ->toArray();

        // 3. Fetch all assignment submissions in ONE query (Keyed by Assignment ID)
        $submissions = \App\Models\Submission::where('student_id', $studentId)
            ->whereIn('assignment_id', $course->assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        // 4. Fetch all quiz attempts in ONE query (Keyed by Quiz ID)
        $quizAttempts = \App\Models\QuizAttempt::where('student_id', $studentId)
            ->whereIn('quiz_id', $course->quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');

        // 5. Fetch all attendance records in ONE query (Keyed by Attendance ID)
        $attendancesRecords = \App\Models\AttendanceStudent::where('student_id', $studentId)
            ->whereIn('attendance_id', $course->attendances->pluck('id'))
            ->get()
            ->keyBy('attendance_id');

        return compact('course', 'completedMaterials', 'submissions', 'quizAttempts', 'attendancesRecords');
    }
}
