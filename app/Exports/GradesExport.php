<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\StudentGrade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GradesExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function collection()
    {
        $components = $this->course->gradeComponents()->get();
        $students = $this->course->students()->get();

        $rows = [];
        foreach ($students as $student) {
            $row = [$student->name, $student->email];
            $final = 0;
            foreach ($components as $comp) {
                $grade = StudentGrade::where('student_id', $student->id)
                    ->where('component_id', $comp->id)
                    ->first();
                $score = $grade ? $grade->score : 0;
                $row[] = $score;
                $final += ($score * $comp->weight / 100);
            }
            $row[] = round($final, 2);
            $rows[] = $row;
        }

        return collect($rows);
    }

    public function headings(): array
    {
        $components = $this->course->gradeComponents()->get();
        $headers = ['Name', 'Email'];
        foreach ($components as $comp) {
            $headers[] = $comp->name . ' (' . $comp->weight . '%)';
        }
        $headers[] = 'Final Score';
        return $headers;
    }

    public function title(): string
    {
        return 'Grades - ' . $this->course->title;
    }
}
