

<?php $__env->startSection('title', 'Edit Quiz - ' . $quiz->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Quiz</h5>
                    <a href="<?php echo e(route('teacher.quizzes.show', $quiz)); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Quiz
                    </a>
                </div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('teacher.quizzes.update', $quiz)); ?>" method="POST" id="quizForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Quiz Title</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="title" name="title" value="<?php echo e(old('title', $quiz->title)); ?>" required>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="description" name="description" rows="3"><?php echo e(old('description', $quiz->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <h6>Questions</h6>
                            <div id="questions-container">
                                <?php $__currentLoopData = $quiz->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="question-item card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Question <?php echo e($index + 1); ?></h6>
                                                <button type="button" class="btn btn-danger btn-sm delete-question" 
                                                    onclick="deleteQuestion(this)" <?php echo e($quiz->questions->count() <= 1 ? 'disabled' : ''); ?>>
                                                    <i class="bi bi-trash"></i> Delete Question
                                                </button>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Question Text</label>
                                                <input type="text" class="form-control" 
                                                    name="questions[<?php echo e($index); ?>][question_text]" 
                                                    value="<?php echo e(old("questions.{$index}.question_text", $question->question_text)); ?>" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option A</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[<?php echo e($index); ?>][option_a]" 
                                                        value="<?php echo e(old("questions.{$index}.option_a", $question->option_a)); ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option B</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[<?php echo e($index); ?>][option_b]" 
                                                        value="<?php echo e(old("questions.{$index}.option_b", $question->option_b)); ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option C</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[<?php echo e($index); ?>][option_c]" 
                                                        value="<?php echo e(old("questions.{$index}.option_c", $question->option_c)); ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Option D</label>
                                                    <input type="text" class="form-control" 
                                                        name="questions[<?php echo e($index); ?>][option_d]" 
                                                        value="<?php echo e(old("questions.{$index}.option_d", $question->option_d)); ?>" required>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Correct Answer</label>
                                                    <select class="form-select" 
                                                        name="questions[<?php echo e($index); ?>][correct_answer]" required>
                                                        <option value="a" <?php echo e(old("questions.{$index}.correct_answer", $question->correct_answer) == 'a' ? 'selected' : ''); ?>>Option A</option>
                                                        <option value="b" <?php echo e(old("questions.{$index}.correct_answer", $question->correct_answer) == 'b' ? 'selected' : ''); ?>>Option B</option>
                                                        <option value="c" <?php echo e(old("questions.{$index}.correct_answer", $question->correct_answer) == 'c' ? 'selected' : ''); ?>>Option C</option>
                                                        <option value="d" <?php echo e(old("questions.{$index}.correct_answer", $question->correct_answer) == 'd' ? 'selected' : ''); ?>>Option D</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Points</label>
                                                    <input type="number" class="form-control" 
                                                        name="questions[<?php echo e($index); ?>][points]" 
                                                        value="<?php echo e(old("questions.{$index}.points", $question->points)); ?>" 
                                                        min="1" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <button type="button" class="btn btn-success" onclick="addQuestion()">
                                <i class="bi bi-plus-circle"></i> Add Question
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let questionCount = <?php echo e($quiz->questions->count()); ?>;

function addQuestion() {
    const container = document.getElementById('questions-container');
    const questionHtml = `
        <div class="question-item card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Question ${questionCount + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm delete-question" onclick="deleteQuestion(this)">
                        <i class="bi bi-trash"></i> Delete Question
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Question Text</label>
                    <input type="text" class="form-control" name="questions[${questionCount}][question_text]" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option A</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_a]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option B</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_b]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option C</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_c]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option D</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][option_d]" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correct Answer</label>
                        <select class="form-select" name="questions[${questionCount}][correct_answer]" required>
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Points</label>
                        <input type="number" class="form-control" name="questions[${questionCount}][points]" value="1" min="1" required>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', questionHtml);
    questionCount++;
    updateDeleteButtons();
}

function deleteQuestion(button) {
    const questionItem = button.closest('.question-item');
    questionItem.remove();
    questionCount--;
    updateDeleteButtons();
}

function updateDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.delete-question');
    deleteButtons.forEach(button => {
        button.disabled = deleteButtons.length <= 1;
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\neuroquiz\resources\views/teacher/quizzes/edit.blade.php ENDPATH**/ ?>