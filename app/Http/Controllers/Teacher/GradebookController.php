<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\GradebookExport;
use Maatwebsite\Excel\Facades\Excel;

class GradebookController extends Controller
{
    protected \App\Services\GradeService $gradeService;

    public function __construct(\App\Services\GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function index(Course $course)
    {
        $this->authorize('view', $course);

        // Fetch comprehensive gradebook data directly from Service
        $data = $this->gradeService->getComprehensiveCourseGrades($course);

        return view('teacher.courses.gradebook', [
            'course' => $course,
            'students' => $data['students'],
            'assignments' => $data['assignmentsRaw'],
            'quizzes' => $data['quizzesRaw'],
            'gradebook' => $data['gradebook'],
        ]);
    }

    public function export(Course $course)
    {
        $this->authorize('view', $course);
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\GradebookExport($course->id), 
            'rekap_nilai_' . str_replace(' ', '_', $course->title) . '.xlsx'
        );
    }
}
