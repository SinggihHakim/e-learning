<?php

namespace App\Exports;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradebookExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $courseId;

    public function __construct(int $courseId)
    {
        $this->courseId = $courseId;
    }

    public function view(): View
    {
        $course = Course::findOrFail($this->courseId);
        $students = $course->students()->orderBy('name')->get();
        $assignments = $course->assignments()->orderBy('created_at')->get();
        $quizzes = $course->quizzes()->orderBy('created_at')->get();

        $gradebook = [];
        foreach ($students as $student) {
            $studentData = [
                'student' => $student,
                'assignments' => [],
                'quizzes' => [],
                'total_score' => 0,
                'item_count' => 0,
            ];

            foreach ($assignments as $assignment) {
                $submission = $assignment->submissions()->where('student_id', $student->id)->first();
                $score = $submission && $submission->score !== null ? (float)$submission->score : null;
                $studentData['assignments'][$assignment->id] = $score;
                if ($score !== null) {
                    $studentData['total_score'] += $score;
                    $studentData['item_count']++;
                }
            }

            foreach ($quizzes as $quiz) {
                $attempt = $quiz->attempts()->where('student_id', $student->id)->orderByDesc('score')->first();
                $score = $attempt && $attempt->score !== null ? (float)$attempt->score : null;
                $studentData['quizzes'][$quiz->id] = $score;
                if ($score !== null) {
                    $studentData['total_score'] += $score;
                    $studentData['item_count']++;
                }
            }

            $studentData['average_score'] = $studentData['item_count'] > 0 
                ? $studentData['total_score'] / $studentData['item_count'] 
                : 0;

            $gradebook[] = $studentData;
        }

        return view('exports.gradebook', compact('course', 'students', 'assignments', 'quizzes', 'gradebook'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
