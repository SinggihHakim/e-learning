<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected \App\Services\Student\CourseService $courseService;

    public function __construct(\App\Services\Student\CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Menampilkan dasbor kelas untuk siswa (Kelas saya & Kelas Tersedia).
     */
    public function index()
    {
        $student = auth()->user();
        
        $myCourses = $student->enrolledCourses()->with('teacher')->get();
        
        $availableCourses = Course::whereDoesntHave('students', fn($q) => $q->where('users.id', $student->id))
            ->with('teacher')
            ->withCount('students')
            ->get();
            
        return view('student.courses.index', compact('myCourses', 'availableCourses'));
    }

    /**
     * Memfasilitasi pendaftaran siswa ke sebuah kelas.
     */
    public function enroll(Course $course)
    {
        $student = auth()->user();
        
        $isEnrolled = $this->courseService->enrollStudent($student, $course);

        if ($isEnrolled) {
            return back()->with('success', __('Anda berhasil mendaftar ke kelas :title', ['title' => $course->title]));
        }

        return back()->with('info', __('Anda sudah terdaftar di kelas ini.'));
    }

    /**
     * Memaparkan ruang kelas lengkap dengan matriks progres belajar siswa.
     */
    public function show(Course $course)
    {
        $student = auth()->user();
        
        // Memastikan siswa benar-benar terdaftar di kelas tersebut
        if (!$student->enrolledCourses()->where('courses.id', $course->id)->exists()) {
            return redirect()->route('student.courses.index')->with('error', __('Anda tidak terdaftar di kelas ini.'));
        }

        // Delegasikan penarikan ribuan baris data query berat ke Service
        $data = $this->courseService->getStudentCourseOverview($student, $course);

        return view('student.courses.show', $data);
    }
}
