<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();
        
        // Check if user has admin role (role_id = 3)
        if ($user->role_id != 3) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}

