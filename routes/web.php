<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\School\DashboardController as SchoolDashboardController;
use App\Http\Controllers\School\QuizController as SchoolQuizController;

// Guest routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('checkRole:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/leaderboard', [AdminDashboardController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/schools', [AdminDashboardController::class, 'schools'])->name('schools');
        
        Route::resource('quizzes', AdminQuizController::class);
        Route::get('/quizzes/{quiz}/live-control', [AdminQuizController::class, 'liveControl'])->name('quizzes.live-control');
        Route::post('/quizzes/{quiz}/questions', [AdminQuizController::class, 'addQuestion'])->name('quizzes.questions.add');
        Route::delete('/quizzes/{quiz}/questions/{question}', [AdminQuizController::class, 'deleteQuestion'])->name('quizzes.questions.delete');
        Route::post('/quizzes/{quiz}/toggle-live', [AdminQuizController::class, 'toggleLive'])->name('quizzes.toggle-live');
        Route::post('/quizzes/{quiz}/upload-questions', [AdminQuizController::class, 'uploadQuestions'])->name('quizzes.upload-questions');
        Route::get('/download-questions-template', [AdminQuizController::class, 'downloadTemplate'])->name('quizzes.download-template');
        Route::post('/quizzes/{quiz}/questions/{question}/set-current', [AdminQuizController::class, 'setCurrentQuestion'])->name('quizzes.set-current-question');
        Route::post('/quizzes/{quiz}/next-question', [AdminQuizController::class, 'nextQuestion'])->name('quizzes.next-question');
        Route::post('/quizzes/{quiz}/previous-question', [AdminQuizController::class, 'previousQuestion'])->name('quizzes.previous-question');
        Route::post('/quizzes/{quiz}/clear-question', [AdminQuizController::class, 'clearCurrentQuestion'])->name('quizzes.clear-question');
        Route::post('/quizzes/{quiz}/complete', [AdminQuizController::class, 'completeQuiz'])->name('quizzes.complete');
        Route::post('/quizzes/{quiz}/start-timer', [AdminQuizController::class, 'startTimer'])->name('quizzes.start-timer');
        Route::post('/quizzes/{quiz}/reveal-answer', [AdminQuizController::class, 'revealAnswer'])->name('quizzes.reveal-answer');
        Route::post('/quizzes/{quiz}/hide-answer', [AdminQuizController::class, 'hideAnswer'])->name('quizzes.hide-answer');
    });

    // School routes
    Route::prefix('school')->name('school.')->middleware('checkRole:school')->group(function () {
        Route::get('/dashboard', [SchoolDashboardController::class, 'index'])->name('dashboard');
        Route::get('/performance', [SchoolDashboardController::class, 'performance'])->name('performance');
        
        Route::get('/quizzes', [SchoolQuizController::class, 'index'])->name('quizzes.index');
        Route::post('/quizzes/{quiz}/register', [SchoolQuizController::class, 'register'])->name('quizzes.register');
        Route::get('/quizzes/{quiz}/participate', [SchoolQuizController::class, 'participate'])->name('quizzes.participate');
        Route::post('/quizzes/{quiz}/submit', [SchoolQuizController::class, 'submitAnswer'])->name('quizzes.submit');
        Route::get('/quizzes/{quiz}/results', [SchoolQuizController::class, 'results'])->name('quizzes.results');
        Route::get('/quizzes/{quiz}/check-state', [SchoolQuizController::class, 'checkQuizState'])->name('quizzes.check-state');
    });
});

