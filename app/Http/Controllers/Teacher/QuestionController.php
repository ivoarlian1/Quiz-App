<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // ... existing code ...

    public function destroy(Quiz $quiz, Question $question)
    {
        $this->authorize('delete', $quiz);
        
        try {
            // Delete all related answers first
            $question->answers()->delete();
            
            // Delete the question
            $question->delete();
            
            return redirect()->route('teacher.quizzes.show', $quiz)
                ->with('success', 'Question deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting question', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to delete question: ' . $e->getMessage());
        }
    }
} 