@extends('layouts.app')

@section('title', $quiz->title . ' - Neuro Quiz')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $quiz->title }}</h5>
                    <div id="timer" class="badge bg-primary" style="font-size: 1.2em;"></div>
                </div>

                <div class="card-body">
                    <form id="quizForm" action="{{ route('student.quizzes.submit', $quiz) }}" method="POST">
                        @csrf
                        @foreach($quiz->questions as $index => $question)
                            <div class="mb-4 question-container" id="question-{{ $index + 1 }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                                    <span class="badge bg-info">Points: {{ $question->points }}</span>
                                </div>
                                <p class="mb-3">{{ $question->question_text }}</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="a" id="q{{ $question->id }}_a">
                                    <label class="form-check-label" for="q{{ $question->id }}_a">
                                        {{ $question->option_a }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="b" id="q{{ $question->id }}_b">
                                    <label class="form-check-label" for="q{{ $question->id }}_b">
                                        {{ $question->option_b }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="c" id="q{{ $question->id }}_c">
                                    <label class="form-check-label" for="q{{ $question->id }}_c">
                                        {{ $question->option_c }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="d" id="q{{ $question->id }}_d">
                                    <label class="form-check-label" for="q{{ $question->id }}_d">
                                        {{ $question->option_d }}
                                    </label>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to submit your answers?')">
                                Submit Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quiz Progress</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Questions:</span>
                            <strong>{{ $quiz->questions->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Answered:</span>
                            <strong id="answeredCount">0</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Remaining:</span>
                            <strong id="remainingCount">{{ $quiz->questions->count() }}</strong>
                        </div>
                    </div>

                    <div class="question-status mt-4">
                        <h6 class="mb-3">Question Status:</h6>
                        <div class="row g-2">
                            @foreach($quiz->questions as $index => $question)
                                <div class="col-3">
                                    <button type="button" 
                                            class="btn btn-outline-primary w-100 question-nav" 
                                            data-question="{{ $index + 1 }}"
                                            id="nav-{{ $index + 1 }}">
                                        {{ $index + 1 }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <span class="badge bg-success me-2">■</span> Answered
                                <span class="badge bg-outline-primary me-2">■</span> Not Answered
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set waktu tersisa dalam detik
    let timeLeft = {{ $remainingTime }};
    
    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        document.getElementById('timer').textContent = timeString;
        
        if (timeLeft <= 0) {
            document.getElementById('quizForm').submit();
        } else {
            timeLeft--;
            setTimeout(updateTimer, 1000);
        }
    }

    // Track answered questions
    const form = document.getElementById('quizForm');
    const answeredCount = document.getElementById('answeredCount');
    const remainingCount = document.getElementById('remainingCount');
    const totalQuestions = {{ $quiz->questions->count() }};

    function updateAnswerCounts() {
        const answered = form.querySelectorAll('input[type="radio"]:checked').length;
        answeredCount.textContent = answered;
        remainingCount.textContent = totalQuestions - answered;

        // Update question navigation buttons
        document.querySelectorAll('.question-nav').forEach((nav, index) => {
            const questionId = form.querySelector(`input[name="answers[${index + 1}]"]:checked`);
            if (questionId) {
                nav.classList.remove('btn-outline-primary');
                nav.classList.add('btn-success');
            } else {
                nav.classList.remove('btn-success');
                nav.classList.add('btn-outline-primary');
            }
        });
    }

    // Question navigation
    document.querySelectorAll('.question-nav').forEach(nav => {
        nav.addEventListener('click', function() {
            const questionNumber = this.dataset.question;
            document.getElementById('question-' + questionNumber).scrollIntoView({ behavior: 'smooth' });
        });
    });

    form.addEventListener('change', updateAnswerCounts);
    
    // Mulai timer saat halaman dimuat
    updateTimer();
</script>
@endpush
@endsection 