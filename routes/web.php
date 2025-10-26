<?php

use Illuminate\Support\Facades\Route;

// Landing Pages
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// Services Routes
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', function () {
        return view('services.index');
    })->name('index');
    
    // Writing Services
    Route::prefix('writing')->name('writing.')->group(function () {
        Route::get('/essays', function () {
            return view('services.writing.essays');
        })->name('essays');
        
        Route::get('/dissertations', function () {
            return view('services.writing.dissertations');
        })->name('dissertations');
        
        Route::get('/research', function () {
            return view('services.writing.research');
        })->name('research');
        
        Route::get('/cv', function () {
            return view('services.writing.cv');
        })->name('cv');
    });
    
    // Tutoring Services
    Route::prefix('tutoring')->name('tutoring.')->group(function () {
        Route::get('/one-on-one', function () {
            return view('services.tutoring.one-on-one');
        })->name('one-on-one');
        
        Route::get('/group', function () {
            return view('services.tutoring.group');
        })->name('group');
        
        Route::get('/test-prep', function () {
            return view('services.tutoring.test-prep');
        })->name('test-prep');
        
        Route::get('/subjects', function () {
            return view('services.tutoring.subjects');
        })->name('subjects');
    });
    
    // Study Resources
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/notes', function () {
            return view('services.resources.notes');
        })->name('notes');
        
        Route::get('/samples', function () {
            return view('services.resources.samples');
        })->name('samples');
        
        Route::get('/guides', function () {
            return view('services.resources.guides');
        })->name('guides');
        
        Route::get('/citations', function () {
            return view('services.resources.citations');
        })->name('citations');
    });
});

// Knowledge Resources
Route::prefix('knowledge-resources')->name('knowledge-resources.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $courses = collect([
            (object) [
                'id' => 1,
                'title' => 'Advanced Mathematics Course',
                'short_description' => 'Master advanced mathematical concepts with expert guidance.',
                'thumbnail_path' => null,
                'level' => 'advanced',
                'category' => 'mathematics',
                'is_featured' => true,
                'price' => 99.99,
                'discount_price' => null,
                'average_rating' => 4.8,
                'total_reviews' => 124,
                'total_enrollments' => 456,
                'creator' => (object) [
                    'user' => (object) ['name' => 'Dr. Sarah Johnson']
                ]
            ],
            (object) [
                'id' => 2,
                'title' => 'Physics Fundamentals',
                'short_description' => 'Learn the core principles of physics through interactive lessons.',
                'thumbnail_path' => null,
                'level' => 'beginner',
                'category' => 'physics',
                'is_featured' => false,
                'price' => 79.99,
                'discount_price' => 59.99,
                'average_rating' => 4.6,
                'total_reviews' => 89,
                'total_enrollments' => 234,
                'creator' => (object) [
                    'user' => (object) ['name' => 'Prof. Michael Chen']
                ]
            ]
        ]);
        
        $studyResources = collect([
            (object) [
                'id' => 1,
                'title' => 'Chemistry Lab Manual',
                'thumbnail_path' => null,
                'resource_type' => 'pdf',
                'download_count' => 156,
                'is_free' => false,
                'price' => 19.99
            ],
            (object) [
                'id' => 2,
                'title' => 'Statistics Cheat Sheet',
                'thumbnail_path' => null,
                'resource_type' => 'pdf',
                'download_count' => 89,
                'is_free' => true,
                'price' => 0
            ],
            (object) [
                'id' => 3,
                'title' => 'Biology Study Guide',
                'thumbnail_path' => null,
                'resource_type' => 'doc',
                'download_count' => 234,
                'is_free' => false,
                'price' => 15.99
            ]
        ]);
        
        $subjects = collect([]);
        $types = [
            'video' => 'Video',
            'document' => 'Document',
            'slides' => 'Slides',
            'audio' => 'Audio'
        ];
        
        return view('knowledge-resources.index', compact('courses', 'studyResources', 'subjects', 'types'));
    })->name('index');
    
    Route::get('/{resource}', function ($resource) {
        return view('knowledge-resources.show');
    })->name('show');
    
    Route::get('/{resource}/purchase', function ($resource) {
        // Mock knowledge resource for demonstration
        $knowledgeResource = (object) [
            'title' => 'Sample Resource',
            'description' => 'Sample description',
            'price' => 19.99,
            'creator' => (object) ['name' => 'Expert Author'],
            'formatted_file_size' => '2.5 MB',
            'content_type' => 'document',
            'type_icon' => 'file-text'
        ];
        
        return view('knowledge-resources.purchase', compact('knowledgeResource'));
    })->name('purchase');
});

// Additional Routes
Route::get('/experts', function () {
    return view('experts');
})->name('experts');

Route::get('/courses/dashboard', function () {
    return view('courses.dashboard');
})->name('courses.dashboard');

Route::get('/courses/{course}', function ($course) {
    return view('courses.show');
})->name('courses.show');

Route::get('/courses', function () {
    return view('courses.index');
})->name('courses.index');

// Auth Routes - Redirect to appropriate Filament panels
Route::get('/login', function () {
    // Show a simple login selector page or redirect to student login
    return view('auth.login-selector');
})->name('login');

Route::get('/register', function () {
    // Show a simple registration selector page  
    return view('auth.register-selector');
})->name('register');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Dashboard route - Smart redirect based on user role
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $user = auth()->user();
    
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
    
    return redirect('/student');
})->name('dashboard');
