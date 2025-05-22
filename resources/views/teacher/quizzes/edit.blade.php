@extends('layouts.app')

@section('title', 'Edit Quiz - ' . $quiz->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Quiz</h5>
                    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Quiz
                    </a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('teacher.quizzes.update', $quiz) }}" method="POST" id="quizForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Quiz Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <h6>Questions</h6>
                            <div id="questions-container">
                                @foreach($quiz->questions as $index => $question)
                                    <div class="question-item card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Question {{ $index + 1 }}</h6>
                                                <button type="button" class="btn btn-danger btn-sm delete-question" 
                                                    onclick="deleteQuestion(this)" {{ $quiz->questions->count() <= 1 ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i> Delete Question
                                                </button>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Question Text</label>
                                                <input type="text" class="form-control" 
                                                    name="questions[{{ $index }}][question_text]" 
                                                    value="{{ old("questions.{$index}.question_text", $question->question_text) }}" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option A</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[{{ $index }}][option_a]" 
                                                        value="{{ old("questions.{$index}.option_a", $question->option_a) }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option B</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[{{ $index }}][option_b]" 
                                                        value="{{ old("questions.{$index}.option_b", $question->option_b) }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option C</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[{{ $index }}][option_c]" 
                                                        value="{{ old("questions.{$index}.option_c", $question->option_c) }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option D</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[{{ $index }}][option_d]" 
                                                        value="{{ old("questions.{$index}.option_d", $question->option_d) }}" required>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Correct Answer</label>
                                                    <select class="form-select" 
                                                        name="questions[{{ $index }}][correct_answer]" required>
                                                        <option value="a" {{ old("questions.{$index}.correct_answer", $question->correct_answer) == 'a' ? 'selected' : '' }}>Option A</option>
                                                        <option value="b" {{ old("questions.{$index}.correct_answer", $question->correct_answer) == 'b' ? 'selected' : '' }}>Option B</option>
                                                        <option value="c" {{ old("questions.{$index}.correct_answer", $question->correct_answer) == 'c' ? 'selected' : '' }}>Option C</option>
                                                        <option value="d" {{ old("questions.{$index}.correct_answer", $question->correct_answer) == 'd' ? 'selected' : '' }}>Option D</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Points</label>
                                                    <input type="number" class="form-control" 
                                                        name="questions[{{ $index }}][points]" 
                                                        value="{{ old("questions.{$index}.points", $question->points) }}" 
                                                        min="1" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-success" onclick="addQuestion()">
                                <i class="bi bi-plus-circle"></i> Add Question
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let questionCount = {{ $quiz->questions->count() }};

function addQuestion() {
    const container = document.getElementById('questions-container');
    const questionHtml = `
        <div class="question-item card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Question ${questionCount + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm delete-question" onclick="deleteQuestion(this)">
                        <i class="bi bi-trash"></i> Delete Question
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Question Text</label>
                    <input type="text" class="form-control" name="questions[${questionCount}][question_text]" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option A</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_a]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option B</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_b]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option C</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_c]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option D</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_d]" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correct Answer</label>
                        <select class="form-select" name="questions[${questionCount}][correct_answer]" required>
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Points</label>
                        <input type="number" class="form-control" name="questions[${questionCount}][points]" value="1" min="1" required>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', questionHtml);
    questionCount++;
    updateDeleteButtons();
}

function deleteQuestion(button) {
    const questionItem = button.closest('.question-item');
    questionItem.remove();
    questionCount--;
    updateDeleteButtons();
}

function updateDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.delete-question');
    deleteButtons.forEach(button => {
        button.disabled = deleteButtons.length <= 1;
    });
}
</script>
@endpush
@endsection 