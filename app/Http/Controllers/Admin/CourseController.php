<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('teacher');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $courses = $query->latest()->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load('teacher', 'students', 'materials', 'assignments');
        return view('admin.courses.show', compact('course'));
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', __('Kelas berhasil dihapus.'));
    }
}
