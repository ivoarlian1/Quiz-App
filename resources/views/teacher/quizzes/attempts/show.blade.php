@extends('layouts.app')

@section('title', 'Quiz Attempt Details - Neuro Quiz')

@section('content')
<div class="row mb-4">
    <div class="col">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Quiz Attempt Details</h2>
            <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Results
            </a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $quiz->title }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Student Information</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $attempt->user->name }}</p>
                        <p class="mb-1"><strong>Student ID:</strong> {{ $attempt->user->student_id }}</p>
                        <p class="mb-0"><strong>Email:</strong> {{ $attempt->user->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Attempt Information</h6>
                        <p class="mb-1"><strong>Started:</strong> {{ $attempt->started_at->format('M d, Y H:i') }}</p>
                        <p class="mb-1"><strong>Submitted:</strong> {{ $attempt->submitted_at->format('M d, Y H:i') }}</p>
                        <p class="mb-0">
                            <strong>Time Taken:</strong>
                            {{ $attempt->submitted_at->diffInMinutes($attempt->started_at) }} minutes
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6>Score</h6>
                    <div class="progress" style="height: 25px;">
                        @php
                            $percentage = ($attempt->score / $attempt->total_points) * 100;
                        @endphp
                        <div class="progress-bar bg-{{ $percentage >= 70 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}"
                             role="progressbar"
                             style="width: {{ $percentage }}%"
                             aria-valuenow="{{ $percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ $attempt->score }}/{{ $attempt->total_points }} ({{ number_format($percentage, 1) }}%)
                        </div>
                    </div>
                </div>

                <div>
                    <h6>Questions & Answers</h6>
                    @foreach($quiz->questions as $index => $question)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="mb-0">Question {{ $index + 1 }}</h6>
                                    <span class="badge bg-{{ isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === $question->correct_answer ? 'success' : 'danger' }}">
                                        {{ isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === $question->correct_answer ? 'Correct' : 'Incorrect' }}
                                    </span>
                                </div>

                                <p class="mb-3">{{ $question->text }}</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="badge {{ $question->correct_answer === 'A' ? 'bg-success' : (isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'A' ? 'bg-danger' : 'bg-secondary') }}">A</span>
                                            {{ $question->options['A'] }}
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge {{ $question->correct_answer === 'B' ? 'bg-success' : (isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'B' ? 'bg-danger' : 'bg-secondary') }}">B</span>
                                            {{ $question->options['B'] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="badge {{ $question->correct_answer === 'C' ? 'bg-success' : (isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'C' ? 'bg-danger' : 'bg-secondary') }}">C</span>
                                            {{ $question->options['C'] }}
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge {{ $question->correct_answer === 'D' ? 'bg-success' : (isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'D' ? 'bg-danger' : 'bg-secondary') }}">D</span>
                                            {{ $question->options['D'] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <small class="text-muted">
                                        Points: {{ $question->points }} |
                                        Student's Answer: {{ isset($attempt->answers[$question->id]) ? $attempt->answers[$question->id] : 'Not answered' }} |
                                        Correct Answer: {{ $question->correct_answer }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Student's Performance</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Score Breakdown</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Total Questions</td>
                                    <td class="text-end">{{ $quiz->questions->count() }}</td>
                                </tr>
                                <tr>
                                    <td>Correct Answers</td>
                                    <td class="text-end">{{ $attempt->score }}</td>
                                </tr>
                                <tr>
                                    <td>Incorrect Answers</td>
                                    <td class="text-end">{{ $quiz->questions->count() - $attempt->score }}</td>
                                </tr>
                                <tr>
                                    <td>Total Points</td>
                                    <td class="text-end">{{ $attempt->total_points }}</td>
                                </tr>
                                <tr>
                                    <td>Points Earned</td>
                                    <td class="text-end">{{ $attempt->score }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h6>Status</h6>
                    @php
                        $percentage = ($attempt->score / $attempt->total_points) * 100;
                        if ($percentage >= 70) {
                            $status = 'Passed';
                            $statusClass = 'success';
                        } elseif ($percentage >= 50) {
                            $status = 'Average';
                            $statusClass = 'warning';
                        } else {
                            $status = 'Failed';
                            $statusClass = 'danger';
                        }
                    @endphp
                    <div class="alert alert-{{ $statusClass }} mb-0">
                        <strong>{{ $status }}</strong>
                        <p class="mb-0">Score: {{ number_format($percentage, 1) }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 