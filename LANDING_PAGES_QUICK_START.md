# Landing Pages & Knowledge Hub - Quick Start Guide

## ✅ What Has Been Implemented

### Controllers Created (2)
1. ✅ `KnowledgeResourceController.php` - Handles public knowledge hub
2. ✅ `StudentDashboardController.php` - Student dashboard views

### Models Created (1)
1. ✅ `ResourcePurchase.php` - Tracks resource purchases

### Routes Created (1 file)
1. ✅ `knowledge-hub-routes.php` - All knowledge hub and service routes

### Database Migration (1)
1. ✅ `resource_purchases` table migration

---

## 🚀 Quick Setup (5 Minutes)

### Step 1: Add Routes to web.php
```bash
# Open: routes/web.php
# Add at the bottom:

require __DIR__.'/knowledge-hub-routes.php';
```

### Step 2: Run Migration
```bash
php artisan migrate
```

### Step 3: Create Required Directories
```bash
mkdir -p resources/views/services/writing
mkdir -p resources/views/services/tutoring
mkdir -p resources/views/services/resources
mkdir -p resources/views/student/dashboard
```

### Step 4: Update Models (Add Relationships)

**In `Course.php`:**
```php
public function purchases()
{
    return $this->morphMany(ResourcePurchase::class, 'resource');
}
```

**In `StudyResource.php`:**
```php
public function purchases()
{
    return $this->morphMany(ResourcePurchase::class, 'resource');
}
```

---

## 📋 What You Need to Create Next

### Priority 1: Service Subpages (Copy & Customize)

I'll create a template for you. Just duplicate and customize for each service.

#### Template: Writing Services Page
```blade
File: resources/views/services/writing/essays.blade.php

@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Professional <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Essay Writing</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Get expertly written essays from qualified writers with advanced degrees
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105">
                Order Essay Now
            </a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="h-8 w-8 text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Plagiarism-Free</h3>
                <p class="text-gray-600">100% original content with Turnitin report</p>
            </div>
            <!-- Add more features -->
        </div>
    </div>
</section>

<!-- Pricing -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-8">Pricing</h2>
        <div class="bg-white rounded-2xl p-8 shadow-xl">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <div class="text-sm text-gray-600">High School</div>
                    <div class="text-2xl font-bold text-blue-600">$15</div>
                    <div class="text-xs text-gray-500">per page</div>
                </div>
                <!-- Add more tiers -->
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-6">Ready to Get Started?</h2>
        <a href="{{ route('register') }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105">
            Place Your Order
        </a>
    </div>
</section>
@endsection
```

**Duplicate this for:**
- `research-papers.blade.php` (change blue to green)
- `dissertations.blade.php` (change blue to indigo)
- `one-on-one.blade.php` (tutoring - use green)
- `group-sessions.blade.php` (tutoring - use teal)
- `test-prep.blade.php` (tutoring - use orange)
- `notes.blade.php` (resources - use purple)
- `guides.blade.php` (resources - use pink)

---

### Priority 2: Knowledge Hub Views

#### Enhanced index.blade.php (Replace existing)
```blade
File: resources/views/knowledge-resources/index.blade.php

@extends('layouts.landing')

@section('content')
<!-- Hero stays the same -->

<!-- Resources Grid (Connect to Database) -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Courses Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8">Featured Courses</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($courses as $course)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 group">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 rounded-t-xl relative">
                        <div class="absolute top-4 right-4">
                            <span class="bg-white px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $course->level }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">By {{ $course->creator->name }}</span>
                            <div class="flex items-center">
                                <i data-lucide="star" class="h-4 w-4 text-yellow-400 fill-current"></i>
                                <span class="ml-1 text-sm">{{ number_format($course->average_rating, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold">${{ number_format($course->price, 2) }}</span>
                            <a href="{{ route('knowledge-resources.show', ['id' => $course->id, 'type' => 'course']) }}" 
                               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No courses available yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Study Resources Section -->
        <div>
            <h2 class="text-3xl font-bold mb-8">Study Resources</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @forelse($studyResources as $resource)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all">
                    <div class="h-32 bg-gradient-to-br from-purple-400 to-pink-500 rounded-t-xl flex items-center justify-center">
                        <i data-lucide="file-text" class="h-12 w-12 text-white"></i>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold mb-2">{{ $resource->title }}</h4>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold">${{ number_format($resource->price, 2) }}</span>
                            <a href="{{ route('knowledge-resources.show', ['id' => $resource->id, 'type' => 'resource']) }}" 
                               class="text-blue-600 text-sm font-medium">
                                View →
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-12">
                    <p class="text-gray-500">No resources available yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
```

---

### Priority 3: Resource Detail Page

```blade
File: resources/views/knowledge-resources/show.blade.php

@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Preview Image/Video -->
                    <div class="h-96 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <i data-lucide="{{ $type === 'course' ? 'play-circle' : 'file-text' }}" class="h-24 w-24 text-white"></i>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-8">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $type === 'course' ? 'Course' : 'Study Resource' }}
                            </span>
                            @if($resource->level)
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                {{ ucfirst($resource->level) }}
                            </span>
                            @endif
                        </div>
                        
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $resource->title }}</h1>
                        
                        <div class="flex items-center gap-6 mb-6 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i data-lucide="user" class="h-4 w-4 mr-2"></i>
                                {{ $resource->creator->name }}
                            </div>
                            @if($type === 'course')
                            <div class="flex items-center">
                                <i data-lucide="users" class="h-4 w-4 mr-2"></i>
                                {{ $resource->enrollments_count ?? 0 }} enrolled
                            </div>
                            @endif
                            <div class="flex items-center">
                                <i data-lucide="star" class="h-4 w-4 mr-2 text-yellow-400 fill-current"></i>
                                {{ number_format($resource->average_rating ?? 0, 1) }}
                            </div>
                        </div>
                        
                        <div class="prose max-w-none mb-8">
                            <h2 class="text-2xl font-bold mb-4">Description</h2>
                            <p class="text-gray-600 leading-relaxed">{{ $resource->description }}</p>
                        </div>
                        
                        @if($type === 'course' && $resource->modules)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold mb-4">Course Content</h2>
                            <div class="space-y-2">
                                @foreach($resource->modules as $module)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i data-lucide="book-open" class="h-5 w-5 text-blue-600 mr-3"></i>
                                            <span class="font-medium">{{ $module->title }}</span>
                                        </div>
                                        @if($userOwns || $loop->first)
                                        <i data-lucide="unlock" class="h-5 w-5 text-green-600"></i>
                                        @else
                                        <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-6">
                    <div class="text-4xl font-bold text-gray-900 mb-6">
                        ${{ number_format($resource->price, 2) }}
                    </div>
                    
                    @if($userOwns)
                    <a href="{{ $type === 'course' ? route('student.dashboard.my-courses') : route('student.dashboard.my-resources') }}" 
                       class="block w-full bg-green-600 text-white text-center py-4 rounded-xl font-semibold mb-4">
                        <i data-lucide="check-circle" class="h-5 w-5 inline mr-2"></i>
                        You Own This
                    </a>
                    @else
                        @auth
                        <a href="{{ route('knowledge-resources.checkout', ['id' => $resource->id, 'type' => $type]) }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition mb-4">
                            Purchase Now
                        </a>
                        @else
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition mb-4">
                            Login to Purchase
                        </a>
                        @endauth
                    @endif
                    
                    <div class="space-y-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="clock" class="h-5 w-5 mr-3 text-gray-400"></i>
                            <span>Lifetime access</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="download" class="h-5 w-5 mr-3 text-gray-400"></i>
                            <span>Downloadable resources</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="certificate" class="h-5 w-5 mr-3 text-gray-400"></i>
                            <span>Certificate of completion</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
```

---

## 🎯 Next Steps Summary

1. ✅ **Add routes** to `web.php`
2. ✅ **Run migration** for purchases table
3. ✅ **Create directories** for views
4. ✅ **Add relationships** to Course and StudyResource models
5. 📝 **Create service subpages** (use template above)
6. 📝 **Create `show.blade.php`** for resource details
7. 📝 **Create dashboard views** for students

## 📚 Full Implementation Plan

See `LANDING_PAGES_IMPLEMENTATION_PLAN.md` for the complete roadmap!

**You're ready to go! Start with the routes and migration, then create the views one by one.** 🚀
