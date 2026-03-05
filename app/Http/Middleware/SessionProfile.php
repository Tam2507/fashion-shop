<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionProfile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if there's a session profile in the query string
        $profile = $request->query('session_profile');
        
        if ($profile) {
            // Store the profile in a cookie
            cookie()->queue('session_profile', $profile, 60 * 24 * 30); // 30 days
        } else {
            // Check if there's a profile in the cookie
            $profile = $request->cookie('session_profile');
        }

        // If we have a profile, change the session cookie name
        if ($profile && preg_match('/^[a-zA-Z0-9_-]+$/', $profile)) {
            config(['session.cookie' => 'laravel_session_' . $profile]);
        }

        return $next($request);
    }
}
