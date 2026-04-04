<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Menampilkan daftar kelas yang diajar oleh guru terkait.
     */
    public function index()
    {
        $courses = Course::where('teacher_id', auth()->id())
            ->withCount('students', 'materials', 'assignments')
            ->latest()
            ->get();
            
        return view('teacher.courses.index', compact('courses'));
    }

    /**
     * Menampilkan formulir pendaftaran kelas baru.
     */
    public function create()
    {
        return view('teacher.courses.create');
    }

    /**
     * Menyimpan data kelas baru ke dalam basis data.
     */
    public function store(\App\Http\Requests\StoreCourseRequest $request)
    {
        $this->courseService->createCourse($request->validated(), auth()->id());

        return redirect()->route('teacher.courses.index')
                         ->with('success', __('Kelas berhasil dibuat.'));
    }

    /**
     * Menampilkan detail informasi dan statistik khusus sebuah kelas.
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);
        
        $course->load('materials', 'assignments', 'gradeComponents', 'quizzes.questions', 'attendances');
        $studentCount = $course->students()->count();
        
        return view('teacher.courses.show', compact('course', 'studentCount'));
    }

    /**
     * Menyajikan daftar paginasi rentetan data siswa (menggunakan AJAX).
     */
    public function studentsPaginated(Request $request, Course $course)
    {
        $this->authorize('view', $course);
        
        if ($request->ajax()) {
            $students = $course->students()->paginate(10);
            return view('teacher.courses.partials.students_table', compact('course', 'students'))->render();
        }
        
        abort(404);
    }

    /**
     * Menampilkan formulir penyuntingan properti kelas.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        
        return view('teacher.courses.edit', compact('course'));
    }

    /**
     * Memperbarui detail profil sebuah kelas.
     */
    public function update(\App\Http\Requests\UpdateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $this->courseService->updateCourse($course, $request->validated());
        
        return redirect()->route('teacher.courses.show', $course)
                         ->with('success', __('Kelas berhasil diperbarui.'));
    }

    /**
     * Menghapus ruangan kelas tersebut secara keseluruhan.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        
        $course->delete();
        
        return redirect()->route('teacher.courses.index')
                         ->with('success', __('Kelas berhasil dihapus.'));
    }

    /**
     * Mencabut hak siswa dari kelas tertuju.
     */
    public function removeStudent(Course $course, User $student)
    {
        $this->authorize('update', $course);
        
        $this->courseService->removeStudent($course, $student->id);
        
        return back()->with('success', __('Siswa berhasil dihapus dari kelas.'));
    }
}
