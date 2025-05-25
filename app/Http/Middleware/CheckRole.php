<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        Log::info('CheckRole middleware running', [
            'requested_role' => $role,
            'is_authenticated' => Auth::check(),
            'user' => Auth::check() ? Auth::user()->toArray() : null,
            'current_session_role' => session('current_role')
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has the required role in database
        if ($user->role !== $role) {
            // Clear all role-related sessions
            session()->forget(['current_role', 'role_teacher_' . $user->id, 'role_student_' . $user->id]);
            
            // Redirect to role selection if no role or wrong role
            return redirect()->route('role.create');
        }

        // Set current role in session
        session(['current_role' => $role]);

        // Verify session role matches requested role
        if (session('current_role') !== $role) {
            session()->forget(['current_role', 'role_teacher_' . $user->id, 'role_student_' . $user->id]);
            return redirect()->route('role.create');
        }

        Log::info('Role check passed', [
            'user_id' => $user->id,
            'role' => $role,
            'session_role' => session('current_role')
        ]);

        return $next($request);
    }
} 