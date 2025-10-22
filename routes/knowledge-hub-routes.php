<?php

use App\Http\Controllers\KnowledgeResourceController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Knowledge Hub Routes
|--------------------------------------------------------------------------
*/

// Public Knowledge Hub
Route::prefix('knowledge-hub')->name('knowledge-resources.')->group(function () {
    Route::get('/', [KnowledgeResourceController::class, 'index'])->name('index');
    Route::get('/{id}/show', [KnowledgeResourceController::class, 'show'])->name('show');
});

// Protected Routes (Require Auth)
Route::middleware(['auth'])->group(function () {
    Route::prefix('knowledge-hub')->name('knowledge-resources.')->group(function () {
        Route::get('/{id}/checkout', [KnowledgeResourceController::class, 'checkout'])->name('checkout');
        Route::post('/{id}/purchase', [KnowledgeResourceController::class, 'purchase'])->name('purchase');
    });

    // Student Dashboard Knowledge Hub
    Route::prefix('student/dashboard')->name('student.dashboard.')->group(function () {
        Route::get('/knowledge-hub', [StudentDashboardController::class, 'knowledgeHub'])->name('knowledge-hub');
        Route::get('/my-courses', [StudentDashboardController::class, 'myCourses'])->name('my-courses');
        Route::get('/my-resources', [StudentDashboardController::class, 'myResources'])->name('my-resources');
    });
});

// Service Subpages
Route::prefix('services')->name('services.')->group(function () {
    // Writing Services
    Route::get('/writing/essays', fn() => view('services.writing.essays'))->name('writing.essays');
    Route::get('/writing/research-papers', fn() => view('services.writing.research-papers'))->name('writing.research-papers');
    Route::get('/writing/dissertations', fn() => view('services.writing.dissertations'))->name('writing.dissertations');
    
    // Tutoring Services
    Route::get('/tutoring/one-on-one', fn() => view('services.tutoring.one-on-one'))->name('tutoring.one-on-one');
    Route::get('/tutoring/group-sessions', fn() => view('services.tutoring.group-sessions'))->name('tutoring.group-sessions');
    Route::get('/tutoring/test-prep', fn() => view('services.tutoring.test-prep'))->name('tutoring.test-prep');
    
    // Study Resources
    Route::get('/resources/notes', fn() => view('services.resources.notes'))->name('resources.notes');
    Route::get('/resources/guides', fn() => view('services.resources.guides'))->name('resources.guides');
});
