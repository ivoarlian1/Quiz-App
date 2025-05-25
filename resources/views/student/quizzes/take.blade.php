@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ $quiz->title }}</span>
                    @if($quiz->time_limit)
                        <div id="timer" class="badge bg-warning text-dark">
                            Time Remaining: <span id="time-remaining">{{ $quiz->time_limit }}:00</span>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('student.quizzes.submit', $quiz) }}" id="quiz-form">
                        @csrf
                        
                        @foreach($quiz->questions as $index => $question)
                            <div class="mb-4">
                                <h5>Question {{ $index + 1 }}</h5>
                                <p>{{ $question->question_text }}</p>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_a" value="a" required>
                                    <label class="form-check-label" for="q{{ $question->id }}_a">
                                        {{ $question->option_a }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_b" value="b">
                                    <label class="form-check-label" for="q{{ $question->id }}_b">
                                        {{ $question->option_b }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_c" value="c">
                                    <label class="form-check-label" for="q{{ $question->id }}_c">
                                        {{ $question->option_c }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_d" value="d">
                                    <label class="form-check-label" for="q{{ $question->id }}_d">
                                        {{ $question->option_d }}
                                    </label>
                                </div>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary">Submit Quiz</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($quiz->time_limit)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timeLimit = {{ $quiz->time_limit }};
            let timeRemaining = timeLimit * 60; // Convert to seconds
            const timerDisplay = document.getElementById('time-remaining');
            const quizForm = document.getElementById('quiz-form');

            function updateTimer() {
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    quizForm.submit();
                } else {
                    timeRemaining--;
                }
            }

            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer(); // Initial update
        });
    </script>
    @endpush
@endif
@endsection 