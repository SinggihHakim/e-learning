<?php

namespace App\Services;

use App\Models\Course;
use App\Models\StudentGrade;
use App\Models\Submission;
use App\Models\QuizAttempt;
use Illuminate\Support\Collection;

class GradeService
{
    /**
     * Menghasilkan peringkat kelas berdasarkan komponen nilai.
     * Sudah dioptimasi agar terhindar dari N+1 query yang lambat.
     */
    public function getCourseLeaderboard(Course $course): Collection
    {
        $components = $course->gradeComponents()->get();
        $students = $course->students()->get();

        // N+1 Optimization: Get ALL student grades for this course in ONE query
        $allStudentGrades = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->whereIn('component_id', $components->pluck('id'))
            ->get()
            ->groupBy('student_id');

        return $students->map(function ($student) use ($components, $allStudentGrades) {
            $finalScore = 0;
            $studentGrades = $allStudentGrades->get($student->id, collect())->keyBy('component_id');

            foreach ($components as $comp) {
                $grade = $studentGrades->get($comp->id);
                $score = $grade ? (float)$grade->score : 0;
                $finalScore += ($score * $comp->weight / 100);
            }
            
            return [
                'student' => $student,
                'score' => round($finalScore, 2)
            ];
        })->sortByDesc('score')->values();
    }

    /**
     * Mengambil perhitungan nilai akhir seluruh siswa beserta rincian penugasan,
     * kuis, dan gradebook dalam satu matriks memori terefisiensi penuh.
     */
    public function getComprehensiveCourseGrades(Course $course): array
    {
        $components = $course->gradeComponents()->with(['assignments', 'quizzes'])->get();
        $students = $course->students()->get();
        $studentIds = $students->pluck('id');

        $assignmentsRaw = $course->assignments()->orderBy('created_at')->get();
        $quizzesRaw = $course->quizzes()->orderBy('created_at')->get();

        // Pre-fetch all submissions, attempts, and existing grades to avoid N+1 queries
        $allSubmissions = Submission::whereIn('assignment_id', $assignmentsRaw->pluck('id'))
            ->whereIn('student_id', $studentIds)
            ->get()
            ->groupBy('student_id');

        $allAttempts = QuizAttempt::whereIn('quiz_id', $quizzesRaw->pluck('id'))
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('score')
            ->get()
            ->groupBy('student_id');

        $allStudentGrades = StudentGrade::whereIn('student_id', $studentIds)
            ->whereIn('component_id', $components->pluck('id'))
            ->get()
            ->groupBy('student_id');

        // Build grade matrix and gradebook simultaneously
        $grades = [];
        $gradebook = [];
        $gradesToUpdate = [];

        foreach ($students as $student) {
            $grades[$student->id] = [];
            $finalScore = 0;

            $studentSubmissions = $allSubmissions->get($student->id, collect())->keyBy('assignment_id');
            // get highest attempt per quiz
            $studentAttempts = $allAttempts->get($student->id, collect())->groupBy('quiz_id')->map->first();
            $studentGradesDB = $allStudentGrades->get($student->id, collect())->keyBy('component_id');

            // --- 1. GRADE MATRIX (BY COMPONENT) ---
            foreach ($components as $comp) {
                $score = 0;
                
                if ($comp->assignments->count() > 0 || $comp->quizzes->count() > 0) {
                    $totalPoints = 0;
                    $count = 0;
                    
                    foreach ($comp->assignments as $asgn) {
                        $sub = $studentSubmissions->get($asgn->id);
                        $totalPoints += $sub && $sub->score !== null ? $sub->score : 0;
                        $count++;
                    }
                    foreach ($comp->quizzes as $quiz) {
                        $attempt = $studentAttempts->get($quiz->id);
                        $totalPoints += $attempt && $attempt->score !== null ? $attempt->score : 0;
                        $count++;
                    }
                    $score = $count > 0 ? round($totalPoints / $count, 2) : 0;
                    
                    // Note for DB sync: check if changed before queueing write to save performance
                    $existingGrade = $studentGradesDB->get($comp->id);
                    if (!$existingGrade || $existingGrade->score != $score) {
                        $gradesToUpdate[] = [
                            'student_id' => $student->id,
                            'component_id' => $comp->id,
                            'score' => $score
                        ];
                    }
                } else {
                    $grade = $studentGradesDB->get($comp->id);
                    $score = $grade ? $grade->score : 0;
                }

                $grades[$student->id][$comp->id] = $score;
                $finalScore += ($score * $comp->weight / 100);
            }
            
            $finalRounded = round($finalScore, 2);
            $grades[$student->id]['final'] = $finalRounded;
            $grades[$student->id]['letter'] = $this->getLetterGrade($finalRounded);

            // --- 2. RAW SCORES (GRADEBOOK) ---
            $studentData = [
                'student' => $student,
                'assignments' => [],
                'quizzes' => [],
                'total_score' => 0,
                'item_count' => 0,
            ];

            foreach ($assignmentsRaw as $assignment) {
                $submission = $studentSubmissions->get($assignment->id);
                $scoreRaw = $submission && $submission->score !== null ? (float)$submission->score : null;
                $studentData['assignments'][$assignment->id] = $scoreRaw;
                
                if ($scoreRaw !== null) {
                    $studentData['total_score'] += $scoreRaw;
                    $studentData['item_count']++;
                }
            }

            foreach ($quizzesRaw as $quiz) {
                $attempt = $studentAttempts->get($quiz->id);
                $scoreRaw = $attempt && $attempt->score !== null ? (float)$attempt->score : null;
                $studentData['quizzes'][$quiz->id] = $scoreRaw;
                
                if ($scoreRaw !== null) {
                    $studentData['total_score'] += $scoreRaw;
                    $studentData['item_count']++;
                }
            }

            $studentData['average_score'] = $studentData['item_count'] > 0 
                ? $studentData['total_score'] / $studentData['item_count'] 
                : 0;

            $gradebook[] = $studentData;
        }

        // Batch update grades if any differ to avoid N+1 writes
        foreach ($gradesToUpdate as $gu) {
            StudentGrade::updateOrCreate(
                ['student_id' => $gu['student_id'], 'component_id' => $gu['component_id']],
                ['score' => $gu['score']]
            );
        }

        // Calculate mapped components efficiently without DB queries
        $mappedComponents = [];
        foreach ($components as $comp) {
            $mappedComponents[$comp->id] = $comp->assignments->isNotEmpty() || $comp->quizzes->isNotEmpty();
        }

        return [
            'components' => $components,
            'students' => $students,
            'grades' => $grades,
            'assignmentsRaw' => $assignmentsRaw,
            'quizzesRaw' => $quizzesRaw,
            'gradebook' => $gradebook,
            'mappedComponents' => $mappedComponents
        ];
    }

    /**
     * Konversi nilai angka ke huruf
     */
    private function getLetterGrade(float $score): string
    {
        if ($score >= 85) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 55) return 'C';
        if ($score >= 40) return 'D';
        return 'E';
    }
}
