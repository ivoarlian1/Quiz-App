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
            'user' => Auth::check() ? Auth::user()->toArray() : null
        ]);

        if (!Auth::check()) {
            Log::warning('User not authenticated');
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->role) {
            Log::warning('User has no role assigned');
            return redirect()->route('role.create');
        }

        Log::info('Checking role', [
            'user_role' => $user->role,
            'required_role' => $role
        ]);

        if ($user->role !== $role) {
            Log::warning('Unauthorized: User role does not match required role', [
                'user_role' => $user->role,
                'required_role' => $role
            ]);
            abort(403, 'Unauthorized action.');
        }

        Log::info('Role check passed');
        return $next($request);
    }
} 