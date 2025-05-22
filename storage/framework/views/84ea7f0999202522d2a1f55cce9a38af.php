

<?php $__env->startSection('title', 'Teacher Dashboard - Neuro Quiz'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col">
        <h2>Welcome, <?php echo e(auth()->user()->name); ?>!</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Quizzes</h5>
                <p class="card-text display-4"><?php echo e($stats['total_quizzes']); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Active Quizzes</h5>
                <p class="card-text display-4"><?php echo e($stats['active_quizzes']); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Attempts</h5>
                <p class="card-text display-4"><?php echo e($stats['total_attempts']); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Quizzes</h5>
                <a href="<?php echo e(route('teacher.quizzes.create')); ?>" class="btn btn-primary">Create Quiz</a>
            </div>
            <div class="card-body">
                <?php if($quizzes->isEmpty()): ?>
                    <p class="text-center text-muted my-4">No quizzes created yet.</p>
                <?php else: ?>
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
                                <?php $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($quiz->title); ?></td>
                                        <td><?php echo e($quiz->questions_count); ?></td>
                                        <td><?php echo e($quiz->attempts_count); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($quiz->is_active ? 'success' : 'secondary'); ?>">
                                                <?php echo e($quiz->is_active ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($quiz->created_at->diffForHumans()); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('teacher.quizzes.show', $quiz)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                <a href="<?php echo e(route('teacher.quizzes.results', $quiz)); ?>" class="btn btn-sm btn-outline-info">Results</a>
                                                <form action="<?php echo e(route('teacher.quizzes.toggle', $quiz)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-<?php echo e($quiz->is_active ? 'warning' : 'success'); ?>">
                                                        <?php echo e($quiz->is_active ? 'Deactivate' : 'Activate'); ?>

                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\neuroquiz\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>