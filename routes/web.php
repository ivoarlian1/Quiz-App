<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\QuizController as TeacherQuizController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Teacher\QuestionController as QuestionController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Auth::routes();

// Role Selection Routes - Must be accessible after login
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/role', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
});

// Teacher Routes
Route::middleware(['web', 'auth', \App\Http\Middleware\CheckRole::class.':teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::resource('quizzes', TeacherQuizController::class);
    Route::get('quizzes/{quiz}/results', [TeacherQuizController::class, 'results'])->name('quizzes.results');
    Route::get('quizzes/{quiz}/download', [TeacherQuizController::class, 'downloadResults'])->name('quizzes.download');
    Route::post('quizzes/{quiz}/toggle', [TeacherQuizController::class, 'toggleStatus'])->name('quizzes.toggle');
    Route::delete('quizzes/{quiz}', [TeacherQuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::resource('quizzes.questions', QuestionController::class);
    Route::delete('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
});

// Student Routes
Route::middleware(['web', 'auth', \App\Http\Middleware\CheckRole::class.':student'])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::post('quizzes/take', [StudentQuizController::class, 'take'])->name('quizzes.take');
    Route::get('quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('quizzes.show');
    Route::post('quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('attempts/{attempt}/result', [StudentQuizController::class, 'result'])->name('attempts.result');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
