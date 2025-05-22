

<?php $__env->startSection('title', $quiz->title . ' - Neuro Quiz'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><?php echo e($quiz->title); ?></h5>
                    <div>
                        <a href="<?php echo e(route('teacher.quizzes.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Quizzes
                        </a>
                        <a href="<?php echo e(route('teacher.quizzes.edit', $quiz)); ?>" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Quiz
                        </a>
                        <form action="<?php echo e(route('teacher.quizzes.destroy', $quiz)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Delete Quiz
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Quiz Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-<?php echo e($quiz->is_active ? 'success' : 'danger'); ?>">
                                            <?php echo e($quiz->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Token:</th>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" value="<?php echo e($quiz->token); ?>" readonly>
                                            <button class="btn btn-outline-secondary btn-sm copy-token" data-token="<?php echo e($quiz->token); ?>" type="button">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Share this token with your students to allow them to take the quiz.</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Questions:</th>
                                    <td><?php echo e($quiz->questions->count()); ?></td>
                                </tr>
                                <tr>
                                    <th>Attempts:</th>
                                    <td><?php echo e($quiz->attempts->count()); ?></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?php echo e($quiz->created_at->format('M d, Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Questions</h6>
                            <?php if($quiz->questions->count() > 0): ?>
                                <div class="list-group">
                                    <?php $__currentLoopData = $quiz->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?php echo e($loop->iteration); ?>. <?php echo e($question->question_text); ?></h6>
                                                    <small class="text-muted">
                                                        Points: <?php echo e($question->points); ?>

                                                    </small>
                                                </div>
                                                <div>
                                                    <small class="text-muted">
                                                        Correct Answer: Option <?php echo e(strtoupper($question->correct_answer)); ?>

                                                    </small>
                                                    <form action="<?php echo e(route('teacher.questions.destroy', [$quiz, $question])); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    No questions added yet.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\neuroquiz\resources\views/teacher/quizzes/show.blade.php ENDPATH**/ ?>