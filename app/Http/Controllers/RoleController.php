<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role) {
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->role === 'student') {
                return redirect()->route('student.dashboard');
            }
        }

        return view('auth.role-selection');
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:teacher,student'],
            'student_id' => ['required_if:role,student', 'nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->role = $request->role;
        
        if ($request->role === 'student') {
            $user->student_id = $request->student_id;
        } else {
            $user->student_id = null;
        }
        
        $user->save();

        // Redirect based on role
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        // Fallback
        return redirect()->route('role.create');
    }
} 