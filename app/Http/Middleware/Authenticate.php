<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Redirect admin routes to admin login
        if ($request->is('admin/*')) {
            return route('admin.login');
        }
        
        // For API requests, return null to get JSON response
        if ($request->is('api/*') || $request->expectsJson()) {
            return null;
        }
        
        // Default to admin login for web requests
        return route('admin.login');
    }
    
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        // Always throw JSON exception for API requests
        // This prevents the "Route [login] not defined" error
        throw new AuthenticationException(
            'Unauthenticated.', $guards, null
        );
    }
}
