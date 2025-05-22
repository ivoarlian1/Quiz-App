@extends('layouts.app')

@section('title', 'Select Role - Neuro Quiz')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Select Role</div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('role.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Select your role:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="teacher" value="teacher" {{ old('role') === 'teacher' ? 'checked' : '' }}>
                            <label class="form-check-label" for="teacher">Teacher</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="student" value="student" {{ old('role') === 'student' ? 'checked' : '' }}>
                            <label class="form-check-label" for="student">Student</label>
                        </div>
                    </div>

                    <div class="mb-3" id="studentIdField" style="display: none;">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id') }}">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush 