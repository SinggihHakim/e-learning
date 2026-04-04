<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use App\Models\Notification;
use App\Services\MaterialService;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    protected MaterialService $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    /**
     * Menampilkan daftar materi.
     */
    public function index()
    {
        $courses = Course::where('teacher_id', auth()->id())->pluck('id');
        $materials = Material::whereIn('course_id', $courses)->with('course')->latest()->get();
        return view('teacher.materials.index', compact('materials'));
    }

    /**
     * Menampilkan formulir pendaftaran materi baru.
     */
    public function create(Request $request)
    {
        $courseId = $request->query('course_id');
        $courses = Course::where('teacher_id', auth()->id())->get();
        return view('teacher.materials.create', compact('courses', 'courseId'));
    }

    /**
     * Menyimpan data materi baru ke basis data.
     */
    public function store(\App\Http\Requests\StoreMaterialRequest $request)
    {
        $material = $this->materialService->createMaterial(
            $request->validated(), 
            $request->file('file')
        );

        return redirect()->route('teacher.courses.show', $material->course_id)
                         ->with('success', __('Materi berhasil diunggah.'));
    }

    /**
     * Menampilkan detail materi yang dipilih.
     */
    public function show(Material $material)
    {
        $this->authorize('view', $material);

        $material->load([
            'comments' => function($q) {
                $q->whereNull('parent_id')
                  ->orderByDesc('is_pinned')
                  ->orderByDesc('created_at')
                  ->with(['user', 'replies.user', 'replies' => function($r) {
                      $r->orderBy('created_at', 'asc');
                  }]);
            }, 
            'course'
        ]);

        return view('teacher.materials.show', compact('material'));
    }

    /**
     * Menampilkan formulir penyuntingan materi.
     */
    public function edit(Material $material)
    {
        $this->authorize('update', $material);

        $courses = Course::where('teacher_id', auth()->id())->get();
        return view('teacher.materials.edit', compact('material', 'courses'));
    }

    /**
     * Memperbarui data materi di basis data.
     */
    public function update(\App\Http\Requests\UpdateMaterialRequest $request, Material $material)
    {
        $this->authorize('update', $material);

        $this->materialService->updateMaterial(
            $material, 
            $request->validated(), 
            $request->file('file')
        );

        return redirect()->route('teacher.courses.show', $material->course_id)
                         ->with('success', __('Materi berhasil diperbarui.'));
    }

    /**
     * Menghapus materi beserta fisiknya dari basis data.
     */
    public function destroy(Material $material)
    {
        $this->authorize('delete', $material);

        $material->delete();
        return back()->with('success', __('Materi berhasil dihapus.'));
    }

    /**
     * Menambahkan komentar ke dalam materi.
     */
    public function comment(Request $request, Material $material)
    {
        $this->authorize('view', $material);

        $request->validate([
            'comment' => 'required|string',
            'parent_id' => 'nullable|exists:material_comments,id'
        ]);
        
        $material->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'is_pinned' => false,
        ]);

        return back()->with('success', __('Komentar berhasil ditambahkan.'));
    }

    /**
     * Menyematkan atau melepas sematan komentar.
     */
    public function pinComment(\App\Models\MaterialComment $comment)
    {
        $this->authorize('update', $comment->material);
        
        $comment->update(['is_pinned' => !$comment->is_pinned]);
        
        if ($comment->is_pinned && $comment->user->isStudent()) {
            $comment->user->addExp(10);
            return back()->with('success', __('Komentar berhasil disematkan. Siswa mendapatkan bonus XP!'));
        }

        return back()->with('success', $comment->is_pinned ? __('Komentar berhasil disematkan.') : __('Semat komentar dilepas.'));
    }

    /**
     * Menghapus komentar.
     */
    public function destroyComment(\App\Models\MaterialComment $comment)
    {
        $this->authorize('update', $comment->material);

        $comment->delete();
        return back()->with('success', __('Komentar berhasil dihapus.'));
    }
}
