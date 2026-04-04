<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Notification;
use App\Models\Submission;
use App\Services\AssignmentService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    protected AssignmentService $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Menampilkan halaman indeks awal.
     */
    public function index()
    {
        return redirect()->route('teacher.courses.index');
    }

    /**
     * Menampilkan formulir pembuatan tugas baru.
     */
    public function create(Request $request)
    {
        $courseId = $request->query('course_id');
        $courses = Course::where('teacher_id', auth()->id())->get();
        $components = \App\Models\GradeComponent::whereIn('course_id', $courses->pluck('id'))->get();
        
        return view('teacher.assignments.create', compact('courses', 'courseId', 'components'));
    }

    /**
     * Menyimpan data tugas yang baru dibuat.
     */
    public function store(\App\Http\Requests\StoreAssignmentRequest $request)
    {
        $assignment = $this->assignmentService->createAssignment($request->validated());

        return redirect()->route('teacher.courses.show', $assignment->course_id)
                         ->with('success', __('Tugas berhasil dibuat.'));
    }

    /**
     * Menampilkan detail tugas beserta pekerjaan masuk dari siswa.
     */
    public function show(Assignment $assignment)
    {
        $this->authorize('view', $assignment);

        $submissions = $assignment->submissions()->with('student')->get();
        
        return view('teacher.assignments.show', compact('assignment', 'submissions'));
    }

    /**
     * Menampilkan formulir penyuntingan tugas.
     */
    public function edit(Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $courses = Course::where('teacher_id', auth()->id())->get();
        $components = \App\Models\GradeComponent::whereIn('course_id', $courses->pluck('id'))->get();
        
        return view('teacher.assignments.edit', compact('assignment', 'courses', 'components'));
    }

    /**
     * Memperbarui detail tugas di basis data.
     */
    public function update(\App\Http\Requests\UpdateAssignmentRequest $request, Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $this->assignmentService->updateAssignment($assignment, $request->validated());
        
        return redirect()->route('teacher.assignments.show', $assignment)
                         ->with('success', __('Tugas berhasil diperbarui.'));
    }

    /**
     * Menghapus tugas dari sistem.
     */
    public function destroy(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        $assignment->delete();
        
        return redirect()->route('teacher.courses.show', $assignment->course_id)
                         ->with('success', __('Tugas berhasil dihapus.'));
    }

    /**
     * Menilai dokumen atau jawaban yang dikumpulkan siswa.
     */
    public function gradeSubmission(\App\Http\Requests\GradeSubmissionRequest $request, Submission $submission)
    {
        $this->authorize('grade', $submission->assignment);

        $this->assignmentService->gradeSubmission($submission, $request->validated());
        
        return back()->with('success', __('Pengiriman berhasil dinilai.'));
    }
}
