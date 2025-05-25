@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quiz Attempt Details</h5>
                        <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="btn btn-secondary">Back to Results</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>{{ $quiz->title }}</h4>
                            <p class="text-muted">{{ $quiz->description }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5>Student Information</h5>
                            <p><strong>Name:</strong> {{ $attempt->student->name }}</p>
                            <p><strong>Student ID:</strong> {{ $attempt->student->student_id }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>Score</h6>
                                    <h3>{{ $attempt->score }}/{{ $attempt->total_points }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>Percentage</h6>
                                    <h3>{{ number_format($attempt->percentage, 1) }}%</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>Submission Time</h6>
                                    <h3>{{ $attempt->created_at->format('H:i:s') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Question Details</h5>
                    @foreach($quiz->questions as $index => $question)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">Question {{ $index + 1 }}</h6>
                                    <span class="badge bg-{{ isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === $question->correct_answer ? 'success' : 'danger' }}">
                                        {{ isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === $question->correct_answer ? 'Correct' : 'Incorrect' }}
                                    </span>
                                </div>
                                <p class="mb-3">{{ $question->question_text }}</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>A.</strong> {{ $question->option_a }}
                                            @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'a')
                                                <i class="fas fa-check text-success"></i>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>B.</strong> {{ $question->option_b }}
                                            @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'b')
                                                <i class="fas fa-check text-success"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>C.</strong> {{ $question->option_c }}
                                            @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'c')
                                                <i class="fas fa-check text-success"></i>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>D.</strong> {{ $question->option_d }}
                                            @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'd')
                                                <i class="fas fa-check text-success"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <strong>Correct Answer:</strong> {{ strtoupper($question->correct_answer) }}
                                        @if(isset($attempt->answers[$question->id]))
                                            <br>
                                            <strong>Student's Answer:</strong> {{ strtoupper($attempt->answers[$question->id]) }}
                                        @else
                                            <br>
                                            <strong>Student's Answer:</strong> Not answered
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 