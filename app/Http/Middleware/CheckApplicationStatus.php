<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckApplicationStatus
{
    /**
     * Handle an incoming request for Expert/Tutor/Creator panels.
     * Blocks access if application is pending or rejected.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check application status based on role
        switch ($role) {
            case 'expert':
                $expert = $user->expert;
                if (!$expert) {
                    return $this->denyAccess('No expert application found');
                }
                if ($expert->application_status === 'pending') {
                    return $this->denyAccess('Your expert application is pending review. You will receive an email once it has been approved.');
                }
                if ($expert->application_status === 'rejected') {
                    return $this->denyAccess('Your expert application was rejected. Reason: ' . ($expert->rejection_reason ?? 'Not specified'));
                }
                break;

            case 'tutor':
                $tutor = $user->tutor;
                if (!$tutor) {
                    return $this->denyAccess('No tutor application found');
                }
                if ($tutor->application_status === 'pending') {
                    return $this->denyAccess('Your tutor application is pending review. You will receive an email once it has been approved.');
                }
                if ($tutor->application_status === 'rejected') {
                    return $this->denyAccess('Your tutor application was rejected. Reason: ' . ($tutor->rejection_reason ?? 'Not specified'));
                }
                break;

            case 'creator':
                $creator = $user->contentCreator;
                if (!$creator) {
                    return $this->denyAccess('No content creator application found');
                }
                if ($creator->application_status === 'pending') {
                    return $this->denyAccess('Your content creator application is pending review. You will receive an email once it has been approved.');
                }
                if ($creator->application_status === 'rejected') {
                    return $this->denyAccess('Your content creator application was rejected. Reason: ' . ($creator->rejection_reason ?? 'Not specified'));
                }
                break;
        }

        return $next($request);
    }

    /**
     * Deny access with a message
     */
    private function denyAccess(string $message): Response
    {
        abort(403, $message);
    }
}
