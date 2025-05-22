<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role === 'teacher';
    }

    public function view(User $user, Quiz $quiz)
    {
        return $user->id === $quiz->teacher_id || $user->role === 'student';
    }

    public function create(User $user)
    {
        return $user->role === 'teacher';
    }

    public function update(User $user, Quiz $quiz)
    {
        return $user->id === $quiz->teacher_id;
    }

    public function delete(User $user, Quiz $quiz)
    {
        return $user->id === $quiz->teacher_id;
    }
} 