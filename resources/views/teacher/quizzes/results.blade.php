@extends('layouts.app')

@section('title', $quiz->title . ' Results - Neuro Quiz')

@section('content')
<div class="row mb-4">
    <div class="col">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ $quiz->title }} Results</h2>
            <div>
                <a href="{{ route('teacher.quizzes.download', $quiz) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
                <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-arrow-left"></i> Back to Quiz
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Attempts</h5>
                <p class="card-text display-4">{{ $quiz->attempts_count }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Average Score</h5>
                <p class="card-text display-4">{{ $quiz->average_score ?? '0' }}%</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Highest Score</h5>
                <p class="card-text display-4">{{ $quiz->highest_score ?? '0' }}%</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Lowest Score</h5>
                <p class="card-text display-4">{{ $quiz->lowest_score ?? '0' }}%</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quiz Attempts</h5>
            </div>
            <div class="card-body">
                @if($attempts->isEmpty())
                    <p class="text-center text-muted my-4">No attempts yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Student ID</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Time Taken</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->user->name }}</td>
                                        <td>{{ $attempt->user->student_id }}</td>
                                        <td>{{ $attempt->score }}/{{ $attempt->total_points }}</td>
                                        <td>
                                            @php
                                                $percentage = ($attempt->score / $attempt->total_points) * 100;
                                            @endphp
                                            <div class="progress">
                                                <div class="progress-bar bg-{{ $percentage >= 70 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}"
                                                     role="progressbar"
                                                     style="width: {{ $percentage }}%"
                                                     aria-valuenow="{{ $percentage }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    {{ number_format($percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $timeTaken = $attempt->submitted_at->diffInMinutes($attempt->started_at);
                                            @endphp
                                            {{ $timeTaken }} minutes
                                        </td>
                                        <td>{{ $attempt->submitted_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('teacher.quizzes.attempts.show', ['quiz' => $quiz, 'attempt' => $attempt]) }}" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $attempts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 