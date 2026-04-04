<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Menampilkan laman indeks untuk kuis.
     */
    public function index()
    {
        $courses = Course::where('teacher_id', auth()->id())->pluck('id');
        $quizzes = Quiz::whereIn('course_id', $courses)->with('course')->latest()->get();
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    /**
     * Menampilkan antarmuka pembuat kuis baru.
     */
    public function create(Request $request)
    {
        $courseId = $request->query('course_id');
        $courses = Course::where('teacher_id', auth()->id())->get();
        $components = \App\Models\GradeComponent::whereIn('course_id', $courses->pluck('id'))->get();
        return view('teacher.quizzes.create', compact('courses', 'courseId', 'components'));
    }

    /**
     * Menyimpan informasi basis kuis.
     */
    public function store(\App\Http\Requests\StoreQuizRequest $request)
    {
        $quiz = $this->quizService->createQuiz($request->validated());

        return redirect()->route('teacher.courses.show', $quiz->course_id)
                         ->with('success', __('Kuis berhasil dibuat.'));
    }

    /**
     * Menampilkan halaman ikhtisar kuis beserta pertanyaannya.
     */
    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        
        $quiz->load('questions.options', 'attempts.student');
        return view('teacher.quizzes.show', compact('quiz'));
    }

    /**
     * Menampilkan formulir penyuntingan kuis.
     */
    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        
        $courses = Course::where('teacher_id', auth()->id())->get();
        $components = \App\Models\GradeComponent::whereIn('course_id', $courses->pluck('id'))->get();
        return view('teacher.quizzes.edit', compact('quiz', 'courses', 'components'));
    }

    /**
     * Menyimpan revisi atau detail ubahan kuis.
     */
    public function update(\App\Http\Requests\UpdateQuizRequest $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        
        $this->quizService->updateQuiz($quiz, $request->validated());
        
        return redirect()->route('teacher.courses.show', $quiz->course_id)
                         ->with('success', __('Kuis berhasil diperbarui.'));
    }

    /**
     * Menghapus keseluruhan kuis.
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        
        $quiz->delete();
        
        return back()->with('success', __('Kuis berhasil dihapus.'));
    }

    /**
     * Menyisipkan soal kuis ke dalam sistem basis data.
     */
    public function storeQuestion(\App\Http\Requests\StoreQuestionRequest $request, Quiz $quiz)
    {
        $this->authorize('manage', $quiz);

        $this->quizService->addQuestion($quiz, $request->validated());

        return back()->with('success', __('Pertanyaan berhasil ditambahkan.'));
    }

    /**
     * Menelaah jawaban spesifik dari rekaman pengerjaan siswa.
     */
    public function showAttempt(Quiz $quiz, \App\Models\QuizAttempt $attempt)
    {
        $this->authorize('manage', $quiz);
        
        if ($attempt->quiz_id !== $quiz->id) abort(404);

        $attempt->load(['student', 'answers.question.options']);
        return view('teacher.quizzes.attempt', compact('quiz', 'attempt'));
    }

    /**
     * Merekam penilaian hasil periksa ulang soal essay pada kuis.
     */
    public function gradeAttempt(\App\Http\Requests\GradeAttemptRequest $request, Quiz $quiz, \App\Models\QuizAttempt $attempt)
    {
        $this->authorize('manage', $quiz);
        
        if ($attempt->quiz_id !== $quiz->id) abort(404);

        $score = $this->quizService->calculateAndSaveGrade($quiz, $attempt, $request->validated());

        return redirect()->route('teacher.quizzes.show', $quiz)
                         ->with('success', __('Kuis berhasil dinilai. Nilai akhir: ') . number_format($score, 2));
    }
}
