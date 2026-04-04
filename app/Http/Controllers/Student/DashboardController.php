<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $courses = $student->enrolledCourses()->withCount('materials', 'assignments')->get();
        $pendingAssignments = Assignment::whereHas('course', function ($q) use ($student) {
            $q->whereHas('students', fn($sq) => $sq->where('users.id', $student->id));
        })->whereDoesntHave('submissions', fn($q) => $q->where('student_id', $student->id))
          ->where('deadline', '>', now())
          ->take(5)
          ->with('course')
          ->get();

        $unreadCount = Notification::where('user_id', $student->id)
            ->where('is_read', false)
            ->count();

        $availableCourses = Course::whereDoesntHave('students', fn($q) => $q->where('users.id', $student->id))
            ->with('teacher')
            ->withCount('materials', 'assignments')
            ->take(4)
            ->get();

        return view('student.dashboard', compact('courses', 'pendingAssignments', 'unreadCount', 'availableCourses'));
    }
}
