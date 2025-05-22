@extends('layouts.app')

@section('title', $quiz->title . ' - Neuro Quiz')

@section('content')
<div class="row mb-4">
    <div class="col">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ $quiz->title }}</h2>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <h5 class="mb-0" id="timer"></h5>
                </div>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#submitConfirmModal">
                    <i class="fas fa-paper-plane"></i> Submit Quiz
                </button>
            </div>
        </div>
    </div>
</div>

<form id="quizForm" action="{{ route('student.quizzes.submit', $quiz) }}" method="POST">
    @csrf
    <input type="hidden" name="started_at" value="{{ now() }}">

    <div class="row">
        <div class="col-md-8">
            @foreach($quiz->questions as $index => $question)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Question {{ $index + 1 }}</h5>
                        <p class="card-text">{{ $question->text }}</p>

                        <div class="list-group">
                            @foreach(['A', 'B', 'C', 'D'] as $option)
                                <label class="list-group-item">
                                    <input type="radio"
                                           class="form-check-input me-2"
                                           name="answers[{{ $question->id }}]"
                                           value="{{ $option }}"
                                           required>
                                    {{ $question->options[$option] }}
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-2">
                            <small class="text-muted">Points: {{ $question->points }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">Question Navigator</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($quiz->questions as $index => $question)
                            <div class="col-3">
                                <button type="button"
                                        class="btn btn-outline-primary w-100 question-nav"
                                        data-question="{{ $index + 1 }}">
                                    {{ $index + 1 }}
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Questions:</span>
                            <strong>{{ $quiz->questions->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Answered:</span>
                            <strong id="answeredCount">0</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Remaining:</span>
                            <strong id="remainingCount">{{ $quiz->questions->count() }}</strong>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#submitConfirmModal">
                            <i class="fas fa-paper-plane"></i> Submit Quiz
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit your quiz?</p>
                <p class="mb-0">
                    <strong>Note:</strong> You cannot change your answers after submission.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('quizForm').submit()">
                    Yes, Submit Quiz
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Time's Up Modal -->
<div class="modal fade" id="timeUpModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Time's Up!</h5>
            </div>
            <div class="modal-body">
                <p>Your time is up! The quiz will be submitted automatically.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="document.getElementById('quizForm').submit()">
                    Submit Quiz
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timer functionality
    const duration = 60; // Duration in minutes
    let timeLeft = duration * 60; // Convert to seconds

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('timer').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft === 0) {
            clearInterval(timerInterval);
            new bootstrap.Modal(document.getElementById('timeUpModal')).show();
        } else {
            timeLeft--;
        }
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();

    // Question navigation
    const questionNavs = document.querySelectorAll('.question-nav');
    const questions = document.querySelectorAll('.card');

    questionNavs.forEach(nav => {
        nav.addEventListener('click', function() {
            const questionNumber = this.dataset.question;
            questions[questionNumber - 1].scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Answer tracking
    const form = document.getElementById('quizForm');
    const answeredCount = document.getElementById('answeredCount');
    const remainingCount = document.getElementById('remainingCount');
    const totalQuestions = {{ $quiz->questions->count() }};

    function updateAnswerCounts() {
        const answered = form.querySelectorAll('input[type="radio"]:checked').length;
        answeredCount.textContent = answered;
        remainingCount.textContent = totalQuestions - answered;

        questionNavs.forEach((nav, index) => {
            const questionId = form.querySelector(`input[name="answers[${index + 1}]"]:checked`);
            nav.classList.toggle('btn-primary', questionId !== null);
            nav.classList.toggle('btn-outline-primary', questionId === null);
        });
    }

    form.addEventListener('change', updateAnswerCounts);

    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '';
    });
});
</script>
@endpush 