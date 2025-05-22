@extends('layouts.app')

@section('title', 'Teacher Dashboard - Neuro Quiz')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Quizzes</h5>
                <p class="card-text display-4">{{ $stats['total_quizzes'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Active Quizzes</h5>
                <p class="card-text display-4">{{ $stats['active_quizzes'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Attempts</h5>
                <p class="card-text display-4">{{ $stats['total_attempts'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Quizzes</h5>
                <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">Create Quiz</a>
            </div>
            <div class="card-body">
                @if($quizzes->isEmpty())
                    <p class="text-center text-muted my-4">No quizzes created yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Questions</th>
                                    <th>Attempts</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $quiz->title }}</td>
                                        <td>{{ $quiz->questions_count }}</td>
                                        <td>{{ $quiz->attempts_count }}</td>
                                        <td>
                                            <span class="badge bg-{{ $quiz->is_active ? 'success' : 'secondary' }}">
                                                {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $quiz->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="btn btn-sm btn-outline-info">Results</a>
                                                <form action="{{ route('teacher.quizzes.toggle', $quiz) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $quiz->is_active ? 'warning' : 'success' }}">
                                                        {{ $quiz->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 