<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Course;
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

        $courses = Course::where('teacher_id', auth()->id())->pluck('id');
        $attendances = Attendance::whereIn('course_id', $courses)
            ->with('course')
            ->withCount('attendanceStudents')
            ->latest()
            ->get();
            
        return view('teacher.attendance.index', compact('attendances'));
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Attendance::class);

        $courses = Course::where('teacher_id', auth()->id())->get();
        return view('teacher.attendance.create', compact('courses'));
    }

    public function store(\App\Http\Requests\StoreAttendanceRequest $request)
    {
        $this->attendanceService->createAttendance($request->validated());
        return redirect()->route('teacher.attendance.index')->with('success', __('Sesi presensi berhasil dibuat.'));
    }

    public function edit(Attendance $attendance)
    {
        $this->authorize('update', $attendance);

        $courses = Course::where('teacher_id', auth()->id())->get();
        return view('teacher.attendance.edit', compact('attendance', 'courses'));
    }

    public function update(\App\Http\Requests\UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $this->authorize('update', $attendance);

        $this->attendanceService->updateAttendance($attendance, $request->validated());
        return redirect()->route('teacher.attendance.index')->with('success', __('Sesi presensi berhasil diperbarui.'));
    }

    public function show(Attendance $attendance)
    {
        $this->authorize('view', $attendance);

        $attendance->load(['course.students', 'attendanceStudents.student']);
        
        $enrolled = $attendance->course->students;
        $submissions = $attendance->attendanceStudents->keyBy('student_id');
        
        return view('teacher.attendance.show', compact('attendance', 'enrolled', 'submissions'));
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete', $attendance);

        $this->attendanceService->deleteAttendance($attendance);
        return redirect()->route('teacher.attendance.index')->with('success', __('Sesi presensi berhasil dihapus.'));
    }
}
