

<?php $__env->startSection('title', 'Select Role - Neuro Quiz'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Select Role</div>

            <div class="card-body">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('role.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Select your role:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="teacher" value="teacher" <?php echo e(old('role') === 'teacher' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="teacher">Teacher</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="student" value="student" <?php echo e(old('role') === 'student' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="student">Student</label>
                        </div>
                    </div>

                    <div class="mb-3" id="studentIdField" style="display: none;">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo e(old('student_id')); ?>">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentIdField = document.getElementById('studentIdField');
    const roleInputs = document.querySelectorAll('input[name="role"]');

    function toggleStudentIdField() {
        const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
        studentIdField.style.display = selectedRole === 'student' ? 'block' : 'none';
    }

    roleInputs.forEach(input => {
        input.addEventListener('change', toggleStudentIdField);
    });

    toggleStudentIdField();
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\neuroquiz\resources\views/auth/role-selection.blade.php ENDPATH**/ ?>