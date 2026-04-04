<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected \App\Services\AttendanceService $attendanceService;

    public function __construct(\App\Services\AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        $this->authorize('viewAny', \App\Models\Attendance::class);

        $student = auth()->user();
        $courseIds = $student->enrolledCourses()->pluck('courses.id');

        // Get all attendances for student's courses
        $attendances = Attendance::whereIn('course_id', $courseIds)
            ->with('course')
            ->latest()
            ->get();

        $myRecords = AttendanceStudent::where('student_id', $student->id)
            ->with(['attendance.course'])
            ->latest()
            ->get();

        $recordAttendanceIds = $myRecords->pluck('attendance_id')->toArray();

        // Filter: only show sessions that are NOT yet submitted by this student
        $activeAttendances = $attendances->reject(function ($att) use ($recordAttendanceIds) {
            return in_array($att->id, $recordAttendanceIds);
        });

        $history = $myRecords;

        return view('student.attendance.index', compact('activeAttendances', 'history'));
    }

    public function submit(\App\Http\Requests\SubmitAttendanceRequest $request, Attendance $attendance)
    {
        $this->authorize('view', $attendance);

        try {
            /** @var \App\Models\User $student */
            $student = auth()->user();
            
            $message = $this->attendanceService->submitStudentAttendance(
                $attendance,
                $student,
                $request->password,
                $request->reason
            );

            return redirect()->route('student.attendance.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

