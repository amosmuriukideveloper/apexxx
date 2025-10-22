<?php

namespace App\Http\Middleware;

use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountApproval
{
    /**
     * Handle an incoming request.
     *
     * This middleware checks if Expert, Tutor, or ContentCreator accounts
     * have been approved before allowing access to certain routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        
        // Check Expert status
        $expert = Expert::where('email', $user->email)->first();
        if ($expert) {
            if ($expert->application_status === 'pending') {
                // If trying to access panel routes
                if ($request->is('expert/*') || $request->is('admin/experts/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('warning', 'Your expert application is pending approval. You will be notified once it is reviewed.');
                }
            }
            
            if ($expert->application_status === 'rejected') {
                if ($request->is('expert/*') || $request->is('admin/experts/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Your expert application has been rejected. Please contact support for more information.');
                }
            }

            // Check if account is suspended or inactive
            if (in_array($expert->status, ['suspended', 'inactive'])) {
                if ($request->is('expert/*') || $request->is('admin/experts/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Your expert account is currently ' . $expert->status . '. Please contact support.');
                }
            }
        }
        
        // Check Tutor status
        $tutor = Tutor::where('email', $user->email)->first();
        if ($tutor) {
            if ($tutor->application_status === 'pending') {
                if ($request->is('tutor/*') || $request->is('admin/tutors/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('warning', 'Your tutor application is pending approval. You will be notified once it is reviewed.');
                }
            }
            
            if ($tutor->application_status === 'rejected') {
                if ($request->is('tutor/*') || $request->is('admin/tutors/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Your tutor application has been rejected. Please contact support for more information.');
                }
            }

            if (in_array($tutor->status, ['suspended', 'inactive'])) {
                if ($request->is('tutor/*') || $request->is('admin/tutors/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Your tutor account is currently ' . $tutor->status . '. Please contact support.');
                }
            }
        }
        
        // Check Content Creator status
        $contentCreator = ContentCreator::where('email', $user->email)->first();
        if ($contentCreator) {
            if ($contentCreator->application_status === 'pending') {
                if ($request->is('creator/*') || $request->is('admin/content-creators/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('warning', 'Your content creator application is pending approval. You will be notified once it is reviewed.');
                }
            }
            
            if ($contentCreator->application_status === 'rejected') {
                if ($request->is('creator/*') || $request->is('admin/content-creators/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Your content creator application has been rejected. Please contact support for more information.');
                }
            }

            if (in_array($contentCreator->status, ['suspended', 'inactive'])) {
                if ($request->is('creator/*') || $request->is('admin/content-creators/*')) {
                    auth()->logout();
                    return redirect()->route('login')
                        ->with('error', 'Your content creator account is currently ' . $contentCreator->status . '. Please contact support.');
                }
            }
        }
        
        return $next($request);
    }
}
