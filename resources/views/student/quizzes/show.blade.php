<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }} - Neuro Quiz</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                    </div>
                    <div class="card-body">
                        @if($quiz->description)
                            <div class="mb-4">
                                <p>{{ $quiz->description }}</p>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h6>Quiz Information:</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Questions
                                    <span class="badge bg-primary rounded-pill">{{ $quiz->questions->count() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Points
                                    <span class="badge bg-primary rounded-pill">{{ $quiz->questions->sum('points') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Time Limit
                                    <span class="badge bg-primary rounded-pill">{{ $quiz->time_limit }} minutes</span>
                                </li>
                            </ul>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Important:</strong>
                            <ul class="mb-0 mt-2">
                                <li>You will have {{ $quiz->time_limit }} minutes to complete this quiz.</li>
                                <li>Once you start, the timer cannot be paused.</li>
                                <li>Make sure you have a stable internet connection.</li>
                                <li>Do not refresh or close the browser window during the quiz.</li>
                                <li>Your answers will be automatically submitted when the time is up.</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <form action="{{ route('student.quizzes.attempt', $quiz) }}" method="GET">
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you ready to start the quiz?')">
                                    <i class="bi bi-play-circle"></i> Start Quiz
                                </button>
                            </form>
                            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 