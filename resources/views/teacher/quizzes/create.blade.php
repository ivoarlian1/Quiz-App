@extends('layouts.app')

@section('title', 'Create Quiz - Neuro Quiz')

@section('content')
<div class="container mt-4">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Create Quiz</h2>
                <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Quizzes
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('teacher.quizzes.store') }}" method="POST" id="quizForm">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label">Quiz Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="time_limit" class="form-label">Time Limit (minutes)</label>
                            <input type="number" class="form-control @error('time_limit') is-invalid @enderror" id="time_limit" name="time_limit" value="{{ old('time_limit') }}" min="1" placeholder="Leave empty for no time limit">
                            @error('time_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Set the time limit in minutes. Leave empty if there's no time limit.</div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4>Questions</h4>
                                <button type="button" class="btn btn-success" id="addQuestion">
                                    <i class="bi bi-plus-circle"></i> Add Question
                                </button>
                            </div>

                            @error('questions')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div id="questions">
                                <div class="question-item card mt-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="question-number mb-0">Question 1</h5>
                                            <button type="button" class="btn btn-danger btn-sm remove-question">
                                                <i class="bi bi-trash"></i> Delete Question
                                            </button>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Question Text</label>
                                            <input type="text" class="form-control @error('questions.0.question_text') is-invalid @enderror" name="questions[0][question_text]" value="{{ old('questions.0.question_text') }}" required>
                                            @error('questions.0.question_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Option A</label>
                                                <input type="text" class="form-control @error('questions.0.option_a') is-invalid @enderror" name="questions[0][option_a]" value="{{ old('questions.0.option_a') }}" required>
                                                @error('questions.0.option_a')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Option B</label>
                                                <input type="text" class="form-control @error('questions.0.option_b') is-invalid @enderror" name="questions[0][option_b]" value="{{ old('questions.0.option_b') }}" required>
                                                @error('questions.0.option_b')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Option C</label>
                                                <input type="text" class="form-control @error('questions.0.option_c') is-invalid @enderror" name="questions[0][option_c]" value="{{ old('questions.0.option_c') }}" required>
                                                @error('questions.0.option_c')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Option D</label>
                                                <input type="text" class="form-control @error('questions.0.option_d') is-invalid @enderror" name="questions[0][option_d]" value="{{ old('questions.0.option_d') }}" required>
                                                @error('questions.0.option_d')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Correct Answer</label>
                                                <select class="form-select @error('questions.0.correct_answer') is-invalid @enderror" name="questions[0][correct_answer]" required>
                                                    <option value="">Select correct answer</option>
                                                    <option value="a" {{ old('questions.0.correct_answer') == 'a' ? 'selected' : '' }}>Option A</option>
                                                    <option value="b" {{ old('questions.0.correct_answer') == 'b' ? 'selected' : '' }}>Option B</option>
                                                    <option value="c" {{ old('questions.0.correct_answer') == 'c' ? 'selected' : '' }}>Option C</option>
                                                    <option value="d" {{ old('questions.0.correct_answer') == 'd' ? 'selected' : '' }}>Option D</option>
                                                </select>
                                                @error('questions.0.correct_answer')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Points</label>
                                                <input type="number" class="form-control @error('questions.0.points') is-invalid @enderror" name="questions[0][points]" value="{{ old('questions.0.points', 1) }}" min="1" required>
                                                @error('questions.0.points')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Create Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionsContainer = document.getElementById('questions');
    const addQuestionBtn = document.getElementById('addQuestion');
    let questionCount = 1;

    // Add click event to the first question's remove button
    const firstRemoveBtn = document.querySelector('.remove-question');
    firstRemoveBtn.addEventListener('click', function() {
        if (document.querySelectorAll('.question-item').length > 1) {
            if (confirm('Are you sure you want to delete this question?')) {
                this.closest('.question-item').remove();
                updateQuestionNumbers();
            }
        } else {
            alert('You must have at least one question.');
        }
    });

    addQuestionBtn.addEventListener('click', function() {
        const questionTemplate = document.querySelector('.question-item').cloneNode(true);
        questionCount++;

        // Update question number
        questionTemplate.querySelector('.question-number').textContent = `Question ${questionCount}`;

        // Update input names
        const inputs = questionTemplate.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[0]', `[${questionCount - 1}]`));
                input.value = input.type === 'number' ? '1' : '';
            }
        });

        // Add click event to remove button
        const removeBtn = questionTemplate.querySelector('.remove-question');
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.question-item').length > 1) {
                if (confirm('Are you sure you want to delete this question?')) {
                    questionTemplate.remove();
                    updateQuestionNumbers();
                }
            } else {
                alert('You must have at least one question.');
            }
        });

        questionsContainer.appendChild(questionTemplate);
    });

    function updateQuestionNumbers() {
        const questions = document.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            question.querySelector('.question-number').textContent = `Question ${index + 1}`;
            const inputs = question.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
        });
        questionCount = questions.length;
    }
});
</script>
@endpush 