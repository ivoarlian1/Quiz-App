

<?php $__env->startSection('title', $quiz->title . ' Results - Neuro Quiz'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col">
        <div class="d-flex justify-content-between align-items-center">
            <h2><?php echo e($quiz->title); ?> Results</h2>
            <div>
                <a href="<?php echo e(route('teacher.quizzes.download', $quiz)); ?>" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
                <a href="<?php echo e(route('teacher.quizzes.show', $quiz)); ?>" class="btn btn-outline-primary ms-2">
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
                <p class="card-text display-4"><?php echo e($quiz->attempts_count); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Average Score</h5>
                <p class="card-text display-4"><?php echo e($quiz->average_score ?? '0'); ?>%</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Highest Score</h5>
                <p class="card-text display-4"><?php echo e($quiz->highest_score ?? '0'); ?>%</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Lowest Score</h5>
                <p class="card-text display-4"><?php echo e($quiz->lowest_score ?? '0'); ?>%</p>
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
                <?php if($attempts->isEmpty()): ?>
                    <p class="text-center text-muted my-4">No attempts yet.</p>
                <?php else: ?>
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
                                <?php $__currentLoopData = $attempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($attempt->user->name); ?></td>
                                        <td><?php echo e($attempt->user->student_id); ?></td>
                                        <td><?php echo e($attempt->score); ?>/<?php echo e($attempt->total_points); ?></td>
                                        <td>
                                            <?php
                                                $percentage = ($attempt->score / $attempt->total_points) * 100;
                                            ?>
                                            <div class="progress">
                                                <div class="progress-bar bg-<?php echo e($percentage >= 70 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger')); ?>"
                                                     role="progressbar"
                                                     style="width: <?php echo e($percentage); ?>%"
                                                     aria-valuenow="<?php echo e($percentage); ?>"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    <?php echo e(number_format($percentage, 1)); ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                                $timeTaken = $attempt->submitted_at->diffInMinutes($attempt->started_at);
                                            ?>
                                            <?php echo e($timeTaken); ?> minutes
                                        </td>
                                        <td><?php echo e($attempt->submitted_at->format('M d, Y H:i')); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('teacher.quizzes.attempts.show', ['quiz' => $quiz, 'attempt' => $attempt])); ?>" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($attempts->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\neuroquiz\resources\views/teacher/quizzes/results.blade.php ENDPATH**/ ?>