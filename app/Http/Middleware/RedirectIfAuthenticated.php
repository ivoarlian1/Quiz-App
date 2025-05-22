<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Jika user belum memiliki role, redirect ke halaman pilih role
                if (!$user->role) {
                    return redirect()->route('role.create');
                }

                // Jika user sudah memiliki role, redirect sesuai rolenya
                if ($user->role === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                }

                if ($user->role === 'student') {
                    return redirect()->route('student.dashboard');
                }
            }
        }

        return $next($request);
    }
} 