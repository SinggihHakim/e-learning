<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();
        $courses = Course::where('teacher_id', $teacher->id)->withCount('students')->get();

        $totalStudents = $courses->sum('students_count');
        $totalAssignments = Assignment::whereHas('course', fn($q) => $q->where('teacher_id', $teacher->id))->count();
        $pendingGrading = Submission::whereNull('score')
            ->whereHas('assignment.course', fn($q) => $q->where('teacher_id', $teacher->id))
            ->count();

        return view('teacher.dashboard', compact('courses', 'totalStudents', 'totalAssignments', 'pendingGrading'));
    }
}
