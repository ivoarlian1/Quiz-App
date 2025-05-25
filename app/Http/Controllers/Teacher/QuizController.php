<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;

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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
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
                'time_limit' => $request->time_limit,
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
        
        // Get latest attempt for each student
        $attempts = $quiz->attempts()
            ->with('student')
            ->select('quiz_attempts.*')
            ->join(DB::raw('(
                SELECT student_id, MAX(created_at) as latest_attempt
                FROM quiz_attempts
                WHERE quiz_id = ' . $quiz->id . '
                GROUP BY student_id
            ) as latest'), function($join) {
                $join->on('quiz_attempts.student_id', '=', 'latest.student_id')
                    ->on('quiz_attempts.created_at', '=', 'latest.latest_attempt');
            })
            ->latest()
            ->paginate(10);
        
        $stats = [
            'total_attempts' => $quiz->attempts()->distinct('student_id')->count('student_id'),
            'average_score' => $quiz->attempts()->avg('percentage') ?? 0,
            'highest_score' => $quiz->attempts()->max('score') ?? 0,
            'lowest_score' => $quiz->attempts()->min('score') ?? 0,
        ];

        return view('teacher.quizzes.results', compact('quiz', 'attempts', 'stats'));
    }

    public function downloadResults(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        $attempts = $quiz->attempts()->with('student')->latest()->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header
        $sheet->setCellValue('A1', 'Student Name');
        $sheet->setCellValue('B1', 'Student ID');
        $sheet->setCellValue('C1', 'Score');
        $sheet->setCellValue('D1', 'Percentage');
        $sheet->setCellValue('E1', 'Submission Date');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        
        // Fill data
        $row = 2;
        foreach ($attempts as $attempt) {
            $sheet->setCellValue('A' . $row, $attempt->student->name);
            $sheet->setCellValue('B' . $row, $attempt->student->student_id);
            $sheet->setCellValue('C' . $row, $attempt->score);
            $sheet->setCellValue('D' . $row, number_format($attempt->percentage, 1) . '%');
            $sheet->setCellValue('E' . $row, $attempt->created_at->format('Y-m-d H:i:s'));
            $row++;
        }
        
        // Style data
        $dataStyle = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ];
        $sheet->getStyle('A2:E' . ($row - 1))->applyFromArray($dataStyle);
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'quiz-results-' . $quiz->id . '.xlsx';
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer->save('php://output');
        exit;
    }

    public function showAttempt(Quiz $quiz, QuizAttempt $attempt)
    {
        $this->authorize('view', $quiz);
        
        if ($attempt->quiz_id !== $quiz->id) {
            return redirect()->route('teacher.quizzes.results', $quiz)
                ->with('error', 'Invalid attempt for this quiz.');
        }

        return view('teacher.quizzes.attempt-details', compact('quiz', 'attempt'));
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
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
                'time_limit' => $request->time_limit,
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