<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = auth()->user()->quizzes()->latest()->get();
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('teacher.quizzes.create');
    }

    public function store(Request $request)
    {
        \Log::info('Quiz creation started', ['request' => $request->all()]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
            'questions.*.points' => 'required|integer|min:1',
        ]);

        try {
            \Log::info('Creating quiz');
            $quiz = auth()->user()->quizzes()->create([
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => true,
                'teacher_id' => auth()->id(),
            ]);
            \Log::info('Quiz created', ['quiz' => $quiz->toArray()]);

            foreach ($request->questions as $questionData) {
                \Log::info('Creating question', ['question' => $questionData]);
                $quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'option_a' => $questionData['option_a'],
                    'option_b' => $questionData['option_b'],
                    'option_c' => $questionData['option_c'],
                    'option_d' => $questionData['option_d'],
                    'correct_answer' => $questionData['correct_answer'],
                    'points' => $questionData['points'],
                ]);
            }
            \Log::info('Questions created');

            return redirect()->route('teacher.quizzes.show', $quiz)
                ->with('success', 'Quiz created successfully. Here is your quiz token: ' . $quiz->token);
        } catch (\Exception $e) {
            \Log::error('Error creating quiz', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create quiz: ' . $e->getMessage());
        }
    }

    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        return view('teacher.quizzes.show', compact('quiz'));
    }

    public function toggleStatus(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $quiz->update(['is_active' => !$quiz->is_active]);
        return redirect()->back()->with('success', 'Quiz status updated successfully.');
    }

    public function results(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        $attempts = $quiz->attempts()->with('user')->latest()->get();
        
        $stats = [
            'total_attempts' => $attempts->count(),
            'average_score' => $attempts->avg('score'),
            'highest_score' => $attempts->max('score'),
            'lowest_score' => $attempts->min('score'),
        ];

        return view('teacher.quizzes.results', compact('quiz', 'attempts', 'stats'));
    }

    public function downloadResults(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        $attempts = $quiz->attempts()->with('user')->latest()->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="quiz-results.csv"',
        ];

        $callback = function() use ($attempts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Student ID', 'Score', 'Percentage', 'Submission Date']);

            foreach ($attempts as $attempt) {
                fputcsv($file, [
                    $attempt->user->name,
                    $attempt->user->student_id,
                    $attempt->score,
                    $attempt->percentage . '%',
                    $attempt->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        
        try {
            // Delete all related questions first
            $quiz->questions()->delete();
            
            // Delete all related attempts
            $quiz->attempts()->delete();
            
            // Delete the quiz
            $quiz->delete();
            
            return redirect()->route('teacher.quizzes.index')
                ->with('success', 'Quiz deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting quiz', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to delete quiz: ' . $e->getMessage());
        }
    }

    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        return view('teacher.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
            'questions.*.points' => 'required|integer|min:1',
        ]);

        try {
            // Update quiz details
            $quiz->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            // Delete existing questions
            $quiz->questions()->delete();

            // Create new questions
            foreach ($request->questions as $questionData) {
                $quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'option_a' => $questionData['option_a'],
                    'option_b' => $questionData['option_b'],
                    'option_c' => $questionData['option_c'],
                    'option_d' => $questionData['option_d'],
                    'correct_answer' => $questionData['correct_answer'],
                    'points' => $questionData['points'],
                ]);
            }

            return redirect()->route('teacher.quizzes.show', $quiz)
                ->with('success', 'Quiz updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating quiz', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update quiz: ' . $e->getMessage());
        }
    }
}