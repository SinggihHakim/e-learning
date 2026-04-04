<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Progress;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $courses = $student->enrolledCourses()->with(['teacher', 'materials' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->get();

        $completedIds = Progress::where('student_id', $student->id)
            ->where('completed', true)
            ->pluck('material_id')
            ->toArray();

        return view('student.materials.index', compact('courses', 'completedIds'));
    }

    public function show(Material $material)
    {
        $student = auth()->user();
        if (!$student->enrolledCourses()->where('courses.id', $material->course_id)->exists()) {
            abort(403);
        }
        $material->load([
            'comments' => function($q) {
                // Get only main comments, ordered by pinned first then latest
                $q->whereNull('parent_id')
                  ->orderByDesc('is_pinned')
                  ->orderByDesc('created_at')
                  ->with(['user', 'replies.user', 'replies' => function($r) {
                      $r->orderBy('created_at', 'asc');
                  }]);
            }, 
            'course'
        ]);
        return view('student.materials.show', compact('material'));
    }

    public function markCompleted(Material $material)
    {
        $student = auth()->user();
        $progress = Progress::firstOrNew([
            'student_id' => $student->id, 'material_id' => $material->id
        ]);
        
        $wasCompleted = $progress->completed;
        
        $progress->completed = true;
        $progress->save();
        
        if (!$wasCompleted) {
            $student->addExp(10); // Gamification rule: 10 XP for reading/marking done
            return back()->with('success', __('Materi selesai! +10 XP diraih.'));
        }

        return back()->with('success', __('Materi berhasil ditandai sebagai selesai.'));
    }

    public function comment(Request $request, Material $material)
    {
        $student = auth()->user();
        if (!$student->enrolledCourses()->where('courses.id', $material->course_id)->exists()) {
            abort(403);
        }
        $request->validate([
            'comment' => 'required|string',
            'parent_id' => 'nullable|exists:material_comments,id'
        ], [
            'comment.required' => 'Komentar tidak boleh kosong.',
            'comment.string' => 'Komentar harus berupa teks.'
        ]);
        
        $material->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'is_pinned' => false,
        ]);
        return back()->with('success', __('Komentar berhasil ditambahkan.'));
    }

    public function destroyComment(\App\Models\MaterialComment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Aksi tidak diizinkan. Anda hanya dapat menghapus komentar Anda sendiri.');
        }

        // Opsional: Batas waktu hapus komentar (misalnya 1 jam)
        if ($comment->created_at->diffInMinutes(now()) > 60) {
            return back()->with('error', __('Komentar tidak dapat dihapus setelah melewati 1 jam.'));
        }

        $comment->delete();
        return back()->with('success', __('Komentar berhasil dihapus.'));
    }
}
