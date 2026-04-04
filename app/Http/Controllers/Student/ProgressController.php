<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Progress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $courses = $student->enrolledCourses()->with('materials')->get();

        $progressData = [];
        foreach ($courses as $course) {
            $totalMaterials = $course->materials->count();
            $completedMaterials = Progress::where('student_id', $student->id)
                ->whereIn('material_id', $course->materials->pluck('id'))
                ->where('completed', true)
                ->count();

            $progressData[] = [
                'course' => $course,
                'total_materials' => $totalMaterials,
                'completed_materials' => $completedMaterials,
                'percentage' => $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0,
            ];
        }

        return view('student.progress.index', compact('progressData'));
    }
}
