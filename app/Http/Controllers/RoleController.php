<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        
        // If user already has a role, redirect to their dashboard
        if ($user->role) {
            // Set current role in session
            session(['current_role' => $user->role]);
            return redirect()->route($user->role . '.dashboard');
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
        
        // If user already has a role, don't allow changing it
        if ($user->role) {
            session(['current_role' => $user->role]);
            return redirect()->route($user->role . '.dashboard');
        }

        // Update user's role in database
        $user->role = $request->role;
        
        if ($request->role === 'student') {
            $user->student_id = $request->student_id;
        }
        
        $user->save();

        // Set new role in session
        session(['current_role' => $request->role]);

        Log::info('User role updated', [
            'user_id' => $user->id,
            'new_role' => $request->role,
            'session_role' => session('current_role')
        ]);

        return redirect()->route($request->role . '.dashboard');
    }
} 