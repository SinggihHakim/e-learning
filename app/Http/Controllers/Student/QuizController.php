<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuizController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->enrolledCourses()->pluck('courses.id');
        $quizzes = Quiz::whereIn('course_id', $courses)->with('course')->latest()->get();
        return view('student.quizzes.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        if (!$quiz->course->students->contains(auth()->id())) abort(403);

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', auth()->id())->first();

        return view('student.quizzes.show', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        if (!$quiz->course->students->contains(auth()->id())) abort(403);

        // Prevent double submission
        if (QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', auth()->id())->exists()) {
            return redirect()->route('student.courses.show', $quiz->course_id)->with('error', __('Anda sudah mengerjakan kuis ini.'));
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'start_time' => now(),
            'end_time' => now(), // simplified for now assuming they submit all at once
        ]);

        $totalScore = 0;
        $maxPossibleScore = 0;

        foreach ($quiz->questions as $question) {
            $answerStr = $request->input('answers.' . $question->id);
            $isCorrect = null;
            $pointsAwarded = null;

            if ($question->type === 'multiple_choice') {
                $maxPossibleScore += $question->points;
                $option = $question->options()->find($answerStr);
                if ($option && $option->is_correct) {
                    $isCorrect = true;
                    $pointsAwarded = $question->points;
                    $totalScore += $pointsAwarded;
                } else {
                    $isCorrect = false;
                    $pointsAwarded = 0;
                }
                
                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $question->id,
                    'quiz_question_option_id' => $answerStr,
                    'is_correct' => $isCorrect,
                    'points_awarded' => $pointsAwarded,
                ]);
            } else {
                // Essay
                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $question->id,
                    'essay_answer' => $answerStr,
                ]);
            }
        }

        // If there are no essay questions, we can finalize the score out of 100
        if (!$quiz->questions()->where('type', 'essay')->exists()) {
            if ($maxPossibleScore > 0) {
                $finalScore = round(($totalScore / $maxPossibleScore) * 100);
            } else {
                $finalScore = 0;
            }
            $attempt->update(['score' => $finalScore]);
        }

        auth()->user()->addExp(15);

        return redirect()->route('student.courses.show', $quiz->course_id)->with('success', __('Kuis berhasil dikumpulkan. +15 XP diraih!'));
    }
}
