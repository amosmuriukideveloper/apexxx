<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     * Check if user has an active subscription (if applicable).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Skip subscription check for admin users
        if ($user->hasRole(['super-admin', 'admin'])) {
            return $next($request);
        }

        // Check if user has subscription model/relationship
        if (method_exists($user, 'subscription')) {
            $subscription = $user->subscription;

            // If subscription is required but doesn't exist
            if (!$subscription) {
                return redirect()->route('subscription.required')
                    ->with('error', 'An active subscription is required to access this resource.');
            }

            // Check if subscription is active
            if ($subscription && $subscription->status !== 'active') {
                $message = match($subscription->status) {
                    'expired' => 'Your subscription has expired. Please renew to continue.',
                    'cancelled' => 'Your subscription has been cancelled.',
                    'past_due' => 'Your subscription payment is past due. Please update your payment method.',
                    'suspended' => 'Your subscription has been suspended. Please contact support.',
                    default => 'Your subscription is not active.',
                };

                return redirect()->route('subscription.manage')
                    ->with('error', $message);
            }

            // Check if subscription has expired
            if ($subscription && isset($subscription->expires_at)) {
                if ($subscription->expires_at && $subscription->expires_at->isPast()) {
                    return redirect()->route('subscription.renew')
                        ->with('error', 'Your subscription has expired. Please renew to continue.');
                }
            }
        }

        return $next($request);
    }
}
