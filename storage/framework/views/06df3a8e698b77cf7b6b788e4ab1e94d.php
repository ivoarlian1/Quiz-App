<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quizzes - Neuro Quiz</title>
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
                        <a class="nav-link" href="<?php echo e(route('teacher.dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo e(route('teacher.quizzes.index')); ?>">My Quizzes</a>
                    </li>
                </ul>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-flex">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">My Quizzes</h5>
                        <a href="<?php echo e(route('teacher.quizzes.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create New Quiz
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Token</th>
                                        <th>Questions</th>
                                        <th>Attempts</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($quiz->title); ?></td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm" value="<?php echo e($quiz->token); ?>" readonly>
                                                    <button class="btn btn-outline-secondary btn-sm copy-token" data-token="<?php echo e($quiz->token); ?>" type="button">
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><?php echo e($quiz->questions->count()); ?></td>
                                            <td><?php echo e($quiz->attempts->count()); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($quiz->is_active ? 'success' : 'danger'); ?>">
                                                    <?php echo e($quiz->is_active ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($quiz->created_at->format('M d, Y')); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('teacher.quizzes.show', $quiz)); ?>" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    <a href="<?php echo e(route('teacher.quizzes.results', $quiz)); ?>" class="btn btn-sm btn-success">
                                                        <i class="bi bi-download"></i> Results
                                                    </a>
                                                    <form action="<?php echo e(route('teacher.quizzes.toggle', $quiz)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-<?php echo e($quiz->is_active ? 'warning' : 'success'); ?>">
                                                            <?php echo e($quiz->is_active ? 'Deactivate' : 'Activate'); ?>

                                                        </button>
                                                    </form>
                                                    <form action="<?php echo e(route('teacher.quizzes.destroy', $quiz)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No quizzes created yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const copyButtons = document.querySelectorAll('.copy-token');
        
        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const token = this.dataset.token;
                navigator.clipboard.writeText(token).then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-check"></i>';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                });
            });
        });
    });
    </script>
</body>
</html> <?php /**PATH D:\xampp\htdocs\neuroquiz\resources\views/teacher/quizzes/index.blade.php ENDPATH**/ ?>