<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result - Neuro Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Neuro Quiz</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard</a>
                    </li>
                </ul>
                <form method="POST" action="{{ route('logout') }}" class="d-flex">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Quiz Result</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h4>{{ $attempt->quiz->title }}</h4>
                            <p class="text-muted">Submitted on {{ $attempt->created_at->format('F d, Y H:i') }}</p>
                        </div>

                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Your Score</h5>
                                        <h2 class="display-4 mb-0">{{ $attempt->score }}/{{ $attempt->total_points }}</h2>
                                        <h3 class="mt-2">{{ $attempt->percentage }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Question Review</h5>
                            @foreach($attempt->quiz->questions as $index => $question)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Question {{ $index + 1 }}</h6>
                                        <p class="card-text">{{ $question->question_text }}</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <span class="badge bg-{{ $question->correct_answer === 'a' ? 'success' : 'secondary' }}">A</span>
                                                    {{ $question->option_a }}
                                                    @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'a')
                                                        <i class="bi bi-check-circle-fill text-{{ $question->correct_answer === 'a' ? 'success' : 'danger' }}"></i>
                                                    @endif
                                                </p>
                                                <p class="mb-1">
                                                    <span class="badge bg-{{ $question->correct_answer === 'b' ? 'success' : 'secondary' }}">B</span>
                                                    {{ $question->option_b }}
                                                    @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'b')
                                                        <i class="bi bi-check-circle-fill text-{{ $question->correct_answer === 'b' ? 'success' : 'danger' }}"></i>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <span class="badge bg-{{ $question->correct_answer === 'c' ? 'success' : 'secondary' }}">C</span>
                                                    {{ $question->option_c }}
                                                    @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'c')
                                                        <i class="bi bi-check-circle-fill text-{{ $question->correct_answer === 'c' ? 'success' : 'danger' }}"></i>
                                                    @endif
                                                </p>
                                                <p class="mb-1">
                                                    <span class="badge bg-{{ $question->correct_answer === 'd' ? 'success' : 'secondary' }}">D</span>
                                                    {{ $question->option_d }}
                                                    @if(isset($attempt->answers[$question->id]) && $attempt->answers[$question->id] === 'd')
                                                        <i class="bi bi-check-circle-fill text-{{ $question->correct_answer === 'd' ? 'success' : 'danger' }}"></i>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">Points: {{ $question->points }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 