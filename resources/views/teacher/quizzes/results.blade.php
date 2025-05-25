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
                <p class="card-text display-4">{{ $stats['total_attempts'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Average Score</h5>
                <p class="card-text display-4">{{ number_format($stats['average_score'], 1) }}%</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Highest Score</h5>
                <p class="card-text display-4">{{ $stats['highest_score'] }}/{{ $quiz->total_points }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Lowest Score</h5>
                <p class="card-text display-4">{{ $stats['lowest_score'] }}/{{ $quiz->total_points }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Attempt Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Time Taken</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempts as $attempt)
                        <tr>
                            <td>{{ $attempt->student->name }}</td>
                            <td>{{ $attempt->student->student_id }}</td>
                            <td>{{ $attempt->score }}/{{ $attempt->total_points }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $attempt->percentage >= 70 ? 'success' : ($attempt->percentage >= 50 ? 'warning' : 'danger') }}"
                                         role="progressbar"
                                         style="width: {{ $attempt->percentage }}%"
                                         aria-valuenow="{{ $attempt->percentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ number_format($attempt->percentage, 1) }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $timeTaken = $attempt->created_at->diffInMinutes($attempt->started_at);
                                @endphp
                                {{ $timeTaken }} minutes
                            </td>
                            <td>
                                <a href="{{ route('teacher.quizzes.attempts.show', [$quiz, $attempt]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View Details
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
    </div>
</div>
@endsection 