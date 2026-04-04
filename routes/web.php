<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Student;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return redirect()->route($role . '.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('users/export', [Admin\UserController::class, 'export'])->name('users.export');
    Route::resource('users', Admin\UserController::class);
    Route::resource('courses', Admin\CourseController::class)->only(['index', 'show', 'destroy']);
});

// Teacher Routes
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [Teacher\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('courses', Teacher\CourseController::class);
    Route::get('courses/{course}/students', [Teacher\CourseController::class, 'studentsPaginated'])->name('courses.students');
    Route::delete('courses/{course}/students/{student}', [Teacher\CourseController::class, 'removeStudent'])->name('courses.remove-student');
    Route::get('courses/{course}/gradebook', [Teacher\GradebookController::class, 'index'])->name('courses.gradebook');
    Route::get('courses/{course}/gradebook/export', [Teacher\GradebookController::class, 'export'])->name('courses.gradebook.export');

    Route::resource('materials', Teacher\MaterialController::class);
    Route::post('materials/{material}/comment', [Teacher\MaterialController::class, 'comment'])->name('materials.comment');
    Route::post('materials/comments/{comment}/pin', [Teacher\MaterialController::class, 'pinComment'])->name('materials.comments.pin');
    Route::delete('materials/comments/{comment}', [Teacher\MaterialController::class, 'destroyComment'])->name('materials.comments.destroy');

    Route::resource('assignments', Teacher\AssignmentController::class);
    Route::post('submissions/{submission}/grade', [Teacher\AssignmentController::class, 'gradeSubmission'])->name('submissions.grade');

    Route::resource('quizzes', Teacher\QuizController::class);
    Route::post('quizzes/{quiz}/questions', [Teacher\QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::get('quizzes/{quiz}/attempts/{attempt}', [Teacher\QuizController::class, 'showAttempt'])->name('quizzes.attempts.show');
    Route::put('quizzes/{quiz}/attempts/{attempt}/grade', [Teacher\QuizController::class, 'gradeAttempt'])->name('quizzes.attempts.grade');

    Route::resource('attendance', Teacher\AttendanceController::class);
    Route::get('grades', [Teacher\GradeController::class, 'index'])->name('grades.index');
    Route::get('grades/{course}', [Teacher\GradeController::class, 'show'])->name('grades.show');
    Route::post('grades/{course}/components', [Teacher\GradeController::class, 'storeComponent'])->name('grades.components.store');
    Route::delete('grades/components/{component}', [Teacher\GradeController::class, 'destroyComponent'])->name('grades.components.destroy');
    Route::post('grades/{course}/update', [Teacher\GradeController::class, 'updateGrade'])->name('grades.update');
    Route::get('grades/{course}/export', [Teacher\GradeController::class, 'export'])->name('grades.export');
    Route::get('leaderboard/{course}', [Teacher\GradeController::class, 'leaderboard'])->name('leaderboard');
});

// Student Routes
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');

    Route::get('courses', [Student\CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/{course}', [Student\CourseController::class, 'show'])->name('courses.show');
    Route::post('courses/{course}/enroll', [Student\CourseController::class, 'enroll'])->name('courses.enroll');

    Route::get('materials', [Student\MaterialController::class, 'index'])->name('materials.index');
    Route::get('materials/{material}', [Student\MaterialController::class, 'show'])->name('materials.show');
    Route::post('materials/{material}/complete', [Student\MaterialController::class, 'markCompleted'])->name('materials.complete');
    Route::post('materials/{material}/comment', [Student\MaterialController::class, 'comment'])->name('materials.comment');
    Route::delete('materials/comments/{comment}', [Student\MaterialController::class, 'destroyComment'])->name('materials.comments.destroy');

    Route::get('assignments', [Student\AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('assignments/{assignment}', [Student\AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('assignments/{assignment}/submit', [Student\AssignmentController::class, 'submit'])->name('assignments.submit');

    Route::get('quizzes', [Student\QuizController::class, 'index'])->name('quizzes.index');
    Route::get('quizzes/{quiz}', [Student\QuizController::class, 'show'])->name('quizzes.show');
    Route::post('quizzes/{quiz}/submit', [Student\QuizController::class, 'submit'])->name('quizzes.submit');

    Route::get('attendance', [Student\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/{attendance}/submit', [Student\AttendanceController::class, 'submit'])->name('attendance.submit');

    Route::get('grades', [Student\GradeController::class, 'index'])->name('grades.index');
    Route::get('leaderboard/{course}', [Student\GradeController::class, 'leaderboard'])->name('leaderboard');

    Route::get('progress', [Student\ProgressController::class, 'index'])->name('progress.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification Routes
    Route::get('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.read-all');

    // Chat Routes
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/fetch/{user}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
});

require __DIR__ . '/auth.php';
