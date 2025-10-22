<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemLog;
use Illuminate\Support\Str;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * Logs user activity and system events for auditing purposes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Skip logging for non-authenticated users on certain routes
        if (!auth()->check() && $this->shouldSkipLogging($request)) {
            return $response;
        }
        
        // Only log specific methods
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $response;
        }
        
        try {
            $this->logActivity($request, $response);
        } catch (\Exception $e) {
            // Silently fail - don't break the application if logging fails
            logger()->error('Failed to log activity: ' . $e->getMessage());
        }
        
        return $response;
    }
    
    /**
     * Log the activity
     */
    protected function logActivity(Request $request, Response $response): void
    {
        $user = auth()->user();
        
        SystemLog::create([
            'user_id' => $user?->id,
            'action' => $this->getActionName($request),
            'model_type' => $this->getModelType($request),
            'model_id' => $this->getModelId($request),
            'description' => $this->getDescription($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'metadata' => $this->getMetadata($request),
        ]);
    }
    
    /**
     * Determine if logging should be skipped
     */
    protected function shouldSkipLogging(Request $request): bool
    {
        $skipRoutes = [
            'login',
            'register',
            'password/*',
            'logout',
        ];
        
        foreach ($skipRoutes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get action name from request
     */
    protected function getActionName(Request $request): string
    {
        $method = $request->method();
        
        return match($method) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'unknown',
        };
    }
    
    /**
     * Get model type from request
     */
    protected function getModelType(Request $request): ?string
    {
        $path = $request->path();
        
        // Try to extract model from Filament admin routes
        if (Str::contains($path, 'admin/')) {
            preg_match('/admin\/([^\/]+)/', $path, $matches);
            if (isset($matches[1])) {
                return 'App\\Models\\' . Str::studly(Str::singular($matches[1]));
            }
        }
        
        return null;
    }
    
    /**
     * Get model ID from request
     */
    protected function getModelId(Request $request): ?int
    {
        // Try to get ID from route parameters
        $id = $request->route('record') ?? $request->route('id');
        
        return $id ? (int) $id : null;
    }
    
    /**
     * Get description of the action
     */
    protected function getDescription(Request $request): string
    {
        $user = auth()->user();
        $action = $this->getActionName($request);
        $model = $this->getModelType($request);
        
        if ($user && $model) {
            $modelName = class_basename($model);
            return "{$user->name} {$action} {$modelName}";
        }
        
        if ($user) {
            return "{$user->name} performed {$action} action";
        }
        
        return "Action {$action} performed";
    }
    
    /**
     * Get metadata from request
     */
    protected function getMetadata(Request $request): array
    {
        $metadata = [
            'route' => $request->route()?->getName(),
            'referer' => $request->header('referer'),
        ];
        
        // Add form data for POST/PUT requests (excluding sensitive fields)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $data = $request->except([
                'password',
                'password_confirmation',
                'token',
                '_token',
                '_method',
                'current_password',
            ]);
            
            if (!empty($data)) {
                $metadata['form_data'] = $data;
            }
        }
        
        return $metadata;
    }
}
