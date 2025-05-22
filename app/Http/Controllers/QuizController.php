<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher')->except(['show', 'attempt', 'submit']);
    }

    public function index()
    {
        $quizzes = Auth::user()->quizzes()->latest()->get();
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('teacher.quizzes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.option_a' => ['required', 'string'],
            'questions.*.option_b' => ['required', 'string'],
            'questions.*.option_c' => ['required', 'string'],
            'questions.*.option_d' => ['required', 'string'],
            'questions.*.correct_answer' => ['required', 'in:a,b,c,d'],
            'questions.*.points' => ['required', 'integer', 'min:1'],
        ]);

        $quiz = Quiz::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        foreach ($request->questions as $questionData) {
            $quiz->questions()->create($questionData);
        }

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully. Share this token with your students: ' . $quiz->token);
    }

    public function show(Quiz $quiz)
    {
        if (Auth::user()->isTeacher()) {
            $this->authorize('view', $quiz);
            return view('teacher.quizzes.show', compact('quiz'));
        }

        return view('student.quizzes.show', compact('quiz'));
    }

    public function attempt(Request $request, Quiz $quiz)
    {
        if (!Auth::user()->isStudent()) {
            abort(403);
        }

        $attempt = $quiz->attempts()->create([
            'student_id' => Auth::id(),
            'score' => 0,
            'total_points' => $quiz->total_points,
            'answers' => [],
            'started_at' => now(),
            'submitted_at' => null,
        ]);

        return view('student.quizzes.attempt', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        if (!Auth::user()->isStudent()) {
            abort(403);
        }

        $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'in:a,b,c,d'],
        ]);

        $score = 0;
        foreach ($quiz->questions as $question) {
            if (isset($request->answers[$question->id]) && 
                $question->isCorrect($request->answers[$question->id])) {
                $score += $question->points;
            }
        }

        $attempt = $quiz->attempts()
            ->where('student_id', Auth::id())
            ->whereNull('submitted_at')
            ->latest()
            ->first();

        if (!$attempt) {
            abort(404);
        }

        $attempt->update([
            'score' => $score,
            'answers' => $request->answers,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.quizzes.result', $attempt)
            ->with('success', 'Quiz submitted successfully!');
    }

    public function downloadResults(Quiz $quiz)
    {
        $this->authorize('view', $quiz);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Student Name');
        $sheet->setCellValue('B1', 'Student ID');
        $sheet->setCellValue('C1', 'Score');
        $sheet->setCellValue('D1', 'Percentage');
        $sheet->setCellValue('E1', 'Submitted At');

        $row = 2;
        foreach ($quiz->attempts as $attempt) {
            $sheet->setCellValue('A' . $row, $attempt->student->name);
            $sheet->setCellValue('B' . $row, $attempt->student->student_id);
            $sheet->setCellValue('C' . $row, $attempt->score . '/' . $attempt->total_points);
            $sheet->setCellValue('D' . $row, $attempt->percentage . '%');
            $sheet->setCellValue('E' . $row, $attempt->submitted_at->format('Y-m-d H:i:s'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'quiz_results_' . $quiz->id . '.xlsx';
        $path = storage_path('app/public/' . $filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
} 