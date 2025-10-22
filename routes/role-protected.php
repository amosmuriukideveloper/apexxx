<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role-Protected Routes
|--------------------------------------------------------------------------
|
| These routes demonstrate how to protect routes using roles and permissions
| middleware. Include this file in your main routes/web.php file.
|
*/

// Student-only routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
    
    Route::post('/projects/{project}/request-revision', function ($project) {
        // Request revision logic
        return redirect()->back()->with('success', 'Revision requested');
    })->name('projects.request-revision');
});

// Expert-only routes
Route::middleware(['auth', 'role:expert'])->group(function () {
    Route::get('/expert/dashboard', function () {
        return view('expert.dashboard');
    })->name('expert.dashboard');
    
    Route::post('/projects/{project}/upload-deliverable', function ($project) {
        // Upload deliverable logic
        return redirect()->back()->with('success', 'Deliverable uploaded');
    })->name('projects.upload-deliverable');
});

// Tutor-only routes
Route::middleware(['auth', 'role:tutor'])->group(function () {
    Route::get('/tutor/dashboard', function () {
        return view('tutor.dashboard');
    })->name('tutor.dashboard');
    
    Route::get('/tutoring/schedule', function () {
        return view('tutoring.schedule');
    })->name('tutoring.schedule');
});

// Content Creator-only routes
Route::middleware(['auth', 'role:content_creator'])->group(function () {
    Route::get('/creator/dashboard', function () {
        return view('creator.dashboard');
    })->name('creator.dashboard');
    
    Route::resource('courses', CourseController::class)->except(['index', 'show']);
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::post('/projects/{project}/assign', [ProjectController::class, 'assign'])
        ->name('projects.assign');
    
    Route::post('/projects/{project}/approve', [ProjectController::class, 'approve'])
        ->name('projects.approve');
    
    Route::post('/courses/{course}/approve', [CourseController::class, 'approve'])
        ->name('courses.approve');
});

// Super Admin-only routes
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/super-admin/dashboard', function () {
        return view('super-admin.dashboard');
    })->name('super-admin.dashboard');
    
    Route::get('/system/config', function () {
        return view('system.config');
    })->name('system.config');
    
    Route::get('/users/manage', function () {
        return view('users.manage');
    })->name('users.manage');
});

// Permission-based routes (can be accessed by multiple roles)
Route::middleware(['auth', 'permission:view_analytics'])->group(function () {
    Route::get('/analytics', function () {
        return view('analytics.index');
    })->name('analytics.index');
});

Route::middleware(['auth', 'permission:manage_wallet'])->group(function () {
    Route::get('/wallet', function () {
        return view('wallet.index');
    })->name('wallet.index');
});

Route::middleware(['auth', 'permission:send_messages'])->group(function () {
    Route::get('/messages', function () {
        return view('messages.index');
    })->name('messages.index');
    
    Route::post('/messages', function () {
        // Send message logic
        return redirect()->back()->with('success', 'Message sent');
    })->name('messages.send');
});

// Multi-role routes using Gate
Route::middleware(['auth'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])
        ->name('projects.index');
    
    Route::get('/courses', [CourseController::class, 'index'])
        ->name('courses.index');
    
    // Admin panel access
    Route::get('/admin', function () {
        if (!Gate::allows('access-admin-panel')) {
            abort(403);
        }
        return view('admin.panel');
    })->name('admin.panel');
});
