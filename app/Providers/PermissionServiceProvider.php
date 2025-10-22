<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\PermissionMiddleware;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register middleware aliases
        $router = $this->app['router'];
        $router->aliasMiddleware('role', RoleMiddleware::class);
        $router->aliasMiddleware('permission', PermissionMiddleware::class);

        // Define Gates for common role checks
        Gate::define('access-admin-panel', function ($user) {
            return $user->hasAnyRole(['admin', 'super_admin']);
        });

        Gate::define('manage-projects', function ($user) {
            return $user->can('assign_projects') || $user->can('approve_projects');
        });

        Gate::define('manage-courses', function ($user) {
            return $user->can('create_courses') || $user->can('approve_courses');
        });

        Gate::define('manage-users', function ($user) {
            return $user->can('view_users') || $user->can('edit_users');
        });

        Gate::define('view-analytics', function ($user) {
            return $user->can('view_analytics') || $user->can('view_performance_analytics');
        });
    }
}
