@extends('layouts.app')

@section('title', 'Student Dashboard - Neuro Quiz')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mb-3">
                            <span class="initials">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h5>{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->student_id }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Total Quizzes:</th>
                                <td>{{ $attempts->count() }}</td>
                            </tr>
                            <tr>
                                <th>Average Score:</th>
                                <td>
                                    @php
                                        $avgScore = $attempts->avg('percentage');
                                    @endphp
                                    {{ $avgScore ? number_format($avgScore, 1) . '%' : 'N/A' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Token Input -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Enter Quiz Token</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('student.quizzes.take') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="token" class="form-label">Quiz Token</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('token') is-invalid @enderror" 
                                    id="token" name="token" placeholder="Enter 8-character quiz token" 
                                    maxlength="8" required>
                                <button type="submit" class="btn btn-primary">Start Quiz</button>
                            </div>
                            @error('token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter the quiz token provided by your teacher to start the quiz.</small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Quiz Attempts -->
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Recent Quiz Attempts</h5>
                </div>
                <div class="card-body">
                    @if($attempts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Quiz Title</th>
                                        <th>Score</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attempts as $attempt)
                                        <tr>
                                            <td>{{ $attempt->quiz->title }}</td>
                                            <td>{{ $attempt->percentage }}%</td>
                                            <td>{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $attempt->percentage >= 60 ? 'success' : 'danger' }}">
                                                    {{ $attempt->percentage >= 60 ? 'Passed' : 'Failed' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            You haven't taken any quizzes yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle {
    width: 100px;
    height: 100px;
    background-color: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.initials {
    color: white;
    font-size: 2.5rem;
    font-weight: bold;
}
</style>
@endpush
@endsection 