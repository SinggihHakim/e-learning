<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\GradeComponent;
use App\Models\StudentGrade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected \App\Services\GradeService $gradeService;

    public function __construct(\App\Services\GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function index()
    {
        /** @var \App\Models\User $student */
        $student = auth()->user();
        $courses = $student->enrolledCourses()->with('gradeComponents')->get();

        $courseGrades = [];
        foreach ($courses as $course) {
            $components = $course->gradeComponents;
            $finalScore = 0;
            $recordedGrades = \App\Models\StudentGrade::where('student_id', $student->id)
                ->whereIn('component_id', $components->pluck('id'))
                ->get();
                
            foreach ($components as $comp) {
                $grade = $recordedGrades->where('component_id', $comp->id)->first();
                $score = $grade ? $grade->score : 0;
                $finalScore += ($score * $comp->weight / 100);
            }
            
            $courseGrades[] = [
                'course' => $course,
                'components' => $components,
                'grades' => $recordedGrades,
                'final_score' => round($finalScore, 2),
            ];
        }

        return view('student.grades.index', compact('courseGrades'));
    }

    public function leaderboard(Course $course)
    {
        $this->authorize('view', $course);

        $student = auth()->user();
        $leaderboard = $this->gradeService->getCourseLeaderboard($course);

        return view('student.leaderboard', compact('course', 'leaderboard', 'student'));
    }
}
