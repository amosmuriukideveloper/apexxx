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
        $courses = collect([]);
        $resources = collect([]);
        $subjects = collect([]);
        $types = [
            'video' => 'Video',
            'document' => 'Document',
            'slides' => 'Slides',
            'audio' => 'Audio'
        ];
        
        return view('knowledge-resources.index', compact('courses', 'resources', 'subjects', 'types'));
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

// Auth Routes (placeholder - these would be handled by Laravel Breeze/Jetstream)
Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::get('/register', function () {
    return redirect('/');
})->name('register');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

Route::get('/dashboard', function () {
    return redirect('/');
})->name('dashboard');
