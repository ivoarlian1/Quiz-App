<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $quizzes = $user->quizzes()->latest()->take(5)->get();
        
        $stats = [
            'total_quizzes' => $user->quizzes()->count(),
            'active_quizzes' => $user->quizzes()->where('is_active', true)->count(),
            'total_attempts' => $user->quizzes()->withCount('attempts')->get()->sum('attempts_count'),
        ];

        return view('teacher.dashboard', compact('user', 'quizzes', 'stats'));
    }
} 