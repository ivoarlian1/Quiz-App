<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $attempts = QuizAttempt::where('student_id', $user->id)
            ->whereNotNull('submitted_at')
            ->with('quiz')
            ->latest()
            ->get();

        return view('student.dashboard', compact('user', 'attempts'));
    }

    public function enterToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:8'
        ]);

        $quiz = Quiz::where('token', $request->token)
            ->where('is_active', true)
            ->first();

        if (!$quiz) {
            return redirect()->back()
                ->with('error', 'Invalid or inactive quiz token.');
        }

        // Check if student has already attempted this quiz
        $existingAttempt = QuizAttempt::where('student_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('submitted_at')
            ->first();

        if ($existingAttempt) {
            return redirect()->back()
                ->with('error', 'You have already attempted this quiz.');
        }

        return redirect()->route('student.quizzes.take', $quiz);
    }
} 