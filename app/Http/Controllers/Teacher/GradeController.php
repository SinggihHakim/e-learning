<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\GradeComponent;
use App\Models\StudentGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradesExport;

class GradeController extends Controller
{
    protected \App\Services\GradeService $gradeService;

    public function __construct(\App\Services\GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function index()
    {
        $courses = \App\Models\Course::where('teacher_id', auth()->id())->get();
        return view('teacher.grades.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);

        $data = $this->gradeService->getComprehensiveCourseGrades($course);
        $data['course'] = $course;

        return view('teacher.grades.show', $data);
    }

    public function storeComponent(\App\Http\Requests\StoreGradeComponentRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        \App\Models\GradeComponent::create([
            'course_id' => $course->id,
            'name' => $request->name,
            'weight' => $request->weight,
        ]);
        
        return back()->with('success', __('Komponen penilaian berhasil ditambahkan.'));
    }

    public function destroyComponent(\App\Models\GradeComponent $component)
    {
        $this->authorize('update', $component->course);
        $component->delete();
        
        return back()->with('success', __('Komponen penilaian berhasil dihapus.'));
    }

    public function updateGrade(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'component_id' => 'required|exists:grade_components,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        \App\Models\StudentGrade::updateOrCreate(
            ['student_id' => $request->student_id, 'component_id' => $request->component_id],
            ['score' => $request->score]
        );

        return response()->json(['success' => true]);
    }

    public function export(Course $course)
    {
        $this->authorize('view', $course);
        return Excel::download(new \App\Exports\GradesExport($course), 'grades_' . $course->id . '.xlsx');
    }

    public function leaderboard(Course $course)
    {
        $this->authorize('view', $course);
        
        $leaderboard = $this->gradeService->getCourseLeaderboard($course);

        return view('teacher.leaderboard', compact('course', 'leaderboard'));
    }
}
