<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('student.dashboard')
                ->with('error', 'This quiz is no longer active.');
        }

        return view('student.quizzes.show', compact('quiz'));
    }

    public function attempt(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('student.dashboard')
                ->with('error', 'This quiz is no longer active.');
        }

        // Check if student has already attempted this quiz
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student.quizzes.result', $existingAttempt)
                ->with('error', 'You have already attempted this quiz.');
        }

        return view('student.quizzes.attempt', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('student.dashboard')
                ->with('error', 'This quiz is no longer active.');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:a,b,c,d',
        ]);

        $score = 0;
        $totalPoints = 0;

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            if (isset($request->answers[$question->id]) && 
                $request->answers[$question->id] === $question->correct_answer) {
                $score += $question->points;
            }
        }

        $percentage = ($score / $totalPoints) * 100;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'answers' => $request->answers,
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
        ]);

        return redirect()->route('student.attempts.result', $attempt)
            ->with('success', 'Quiz submitted successfully.');
    }

    public function result(QuizAttempt $attempt)
    {
        if ($attempt->student_id !== auth()->id()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Unauthorized access.');
        }

        return view('student.quizzes.result', compact('attempt'));
    }

    public function take(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:8'
        ]);

        \Log::info('Quiz access attempt', [
            'token' => $request->token,
            'student_id' => auth()->id()
        ]);

        $quiz = Quiz::where('token', $request->token)
            ->where('is_active', true)
            ->first();

        if (!$quiz) {
            \Log::warning('Invalid quiz token', [
                'token' => $request->token,
                'student_id' => auth()->id()
            ]);
            return redirect()->route('student.dashboard')
                ->with('error', 'Invalid or inactive quiz token.');
        }

        // Check if student has already attempted this quiz
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existingAttempt) {
            \Log::info('Student already attempted quiz', [
                'quiz_id' => $quiz->id,
                'student_id' => auth()->id()
            ]);
            return redirect()->route('student.attempts.result', $existingAttempt)
                ->with('error', 'You have already attempted this quiz.');
        }

        \Log::info('Student accessing quiz', [
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id()
        ]);

        return view('student.quizzes.attempt', compact('quiz'));
    }
} 