@extends('layouts.app')

@section('title', 'Select Role - Neuro Quiz')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Select Your Role') }}</div>

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

                        <div class="mb-4">
                            <label for="role" class="form-label">{{ __('I am a') }}</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required onchange="toggleStudentId()">
                                <option value="">{{ __('Select your role') }}</option>
                                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>{{ __('Teacher') }}</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>{{ __('Student') }}</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="studentIdField" class="mb-4" style="display: none;">
                            <label for="student_id" class="form-label">{{ __('Student ID') }}</label>
                            <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                   id="student_id" name="student_id" value="{{ old('student_id') }}" 
                                   placeholder="Enter your student ID">
                            @error('student_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Continue') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStudentId() {
    const roleSelect = document.getElementById('role');
    const studentIdField = document.getElementById('studentIdField');
    
    if (roleSelect.value === 'student') {
        studentIdField.style.display = 'block';
        document.getElementById('student_id').required = true;
    } else {
        studentIdField.style.display = 'none';
        document.getElementById('student_id').required = false;
    }
}

// Run on page load
document.addEventListener('DOMContentLoaded', toggleStudentId);
</script>
@endpush 