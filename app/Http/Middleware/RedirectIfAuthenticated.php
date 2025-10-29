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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect to appropriate dashboard based on role
                if ($user->isSuperAdmin() || $user->isAdmin()) {
                    return redirect('/platform');
                }
                
                if ($user->isExpert()) {
                    return redirect('/expert');
                }
                
                if ($user->isTutor()) {
                    return redirect('/tutor');
                }
                
                if ($user->isContentCreator()) {
                    return redirect('/creator');
                }
                
                // Default to student dashboard
                return redirect('/student');
            }
        }

        return $next($request);
    }
}
