

<?php $__env->startSection('title', 'Student Dashboard - Neuro Quiz'); ?>

<?php $__env->startSection('content'); ?>
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
                            <span class="initials"><?php echo e(substr($user->name, 0, 1)); ?></span>
                        </div>
                        <h5><?php echo e($user->name); ?></h5>
                        <p class="text-muted"><?php echo e($user->student_id); ?></p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <th>Email:</th>
                                <td><?php echo e($user->email); ?></td>
                            </tr>
                            <tr>
                                <th>Total Quizzes:</th>
                                <td><?php echo e($attempts->count()); ?></td>
                            </tr>
                            <tr>
                                <th>Average Score:</th>
                                <td>
                                    <?php
                                        $avgScore = $attempts->avg('percentage');
                                    ?>
                                    <?php echo e($avgScore ? number_format($avgScore, 1) . '%' : 'N/A'); ?>

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
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('student.quizzes.take')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="token" class="form-label">Quiz Token</label>
                            <div class="input-group">
                                <input type="text" class="form-control <?php $__errorArgs = ['token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="token" name="token" placeholder="Enter 8-character quiz token" 
                                    maxlength="8" required>
                                <button type="submit" class="btn btn-primary">Start Quiz</button>
                            </div>
                            <?php $__errorArgs = ['token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <?php if($attempts->count() > 0): ?>
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
                                    <?php $__currentLoopData = $attempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($attempt->quiz->title); ?></td>
                                            <td><?php echo e($attempt->percentage); ?>%</td>
                                            <td><?php echo e($attempt->created_at->format('M d, Y H:i')); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($attempt->percentage >= 60 ? 'success' : 'danger'); ?>">
                                                    <?php echo e($attempt->percentage >= 60 ? 'Passed' : 'Failed'); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            You haven't taken any quizzes yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\neuroquiz\resources\views/student/dashboard.blade.php ENDPATH**/ ?>