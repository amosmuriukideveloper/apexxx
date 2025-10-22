<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user account is suspended
            if (isset($user->status) && $user->status === 'suspended') {
                auth()->logout();
                
                return redirect()->route('login')
                    ->with('error', 'Your account has been suspended. Please contact support for assistance.');
            }
            
            // Check if user account is inactive
            if (isset($user->status) && $user->status === 'inactive') {
                auth()->logout();
                
                return redirect()->route('login')
                    ->with('error', 'Your account is inactive. Please contact support to reactivate your account.');
            }
            
            // Check if user account is deleted
            if (isset($user->status) && $user->status === 'deleted') {
                auth()->logout();
                
                return redirect()->route('login')
                    ->with('error', 'Your account has been deleted.');
            }
        }
        
        return $next($request);
    }
}
