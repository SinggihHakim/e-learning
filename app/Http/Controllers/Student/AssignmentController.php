<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $courseIds = $student->enrolledCourses()->pluck('courses.id');
        $assignments = Assignment::whereIn('course_id', $courseIds)
            ->with('course')
            ->withCount('submissions')
            ->latest()
            ->paginate(10);

        $submittedIds = Submission::where('student_id', $student->id)
            ->pluck('assignment_id')
            ->toArray();

        return redirect()->route('student.courses.index');
    }

    public function show(Assignment $assignment)
    {
        $student = auth()->user();
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();
        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $student = auth()->user();

        // 1. Strict Deadline Check to prevent manipulation
        if (now()->isAfter($assignment->deadline)) {
            return back()->with('error', __('Pengumpulan ditolak! Batas waktu tugas ini sudah terlewat.'));
        }

        // Check if already submitted
        if (Submission::where('assignment_id', $assignment->id)->where('student_id', $student->id)->exists()) {
            return back()->with('error', __('Anda sudah mengumpulkan tugas ini sebelumnya.'));
        }

        // 2. Strict MIME Type/Extension validation to prevent Malware RCE
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,7z,jpg,jpeg,png,webp|max:10240',
        ], [
            'file.mimes' => 'EVALUASI DITOLAK: Format file tidak diizinkan. Harap gunakan dokumen standar (PDF/Word/ZIP/Gambar).',
            'file.max' => 'Ukuran file melampaui batas maksimal 10MB.'
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_path' => $filePath,
        ]);

        $student->addExp(20); // 20 XP for completing an assignment

        return redirect()->route('student.courses.show', $assignment->course_id)->with('success', __('Tugas berhasil dikumpulkan. +20 XP diraih!'));
    }
}
