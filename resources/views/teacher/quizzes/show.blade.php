@extends('layouts.app')

@section('title', $quiz->title . ' - Neuro Quiz')

@section('content')
<div class="container mt-4">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                    <div>
                        <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Quizzes
                        </a>
                        <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Quiz
                        </a>
                        <form action="{{ route('teacher.quizzes.destroy', $quiz) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Delete Quiz
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Quiz Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $quiz->is_active ? 'success' : 'danger' }}">
                                            {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Token:</th>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" value="{{ $quiz->token }}" readonly>
                                            <button class="btn btn-outline-secondary btn-sm copy-token" data-token="{{ $quiz->token }}" type="button">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Share this token with your students to allow them to take the quiz.</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Questions:</th>
                                    <td>{{ $quiz->questions->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Attempts:</th>
                                    <td>{{ $quiz->attempts->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $quiz->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Questions</h6>
                            @if($quiz->questions->count() > 0)
                                <div class="list-group">
                                    @foreach($quiz->questions as $question)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $loop->iteration }}. {{ $question->question_text }}</h6>
                                                    <small class="text-muted">
                                                        Points: {{ $question->points }}
                                                    </small>
                                                </div>
                                                <div>
                                                    <small class="text-muted">
                                                        Correct Answer: Option {{ strtoupper($question->correct_answer) }}
                                                    </small>
                                                    <form action="{{ route('teacher.questions.destroy', [$quiz, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No questions added yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyButtons = document.querySelectorAll('.copy-token');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const token = this.dataset.token;
            navigator.clipboard.writeText(token).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
    });
});
</script>
@endpush
@endsection 