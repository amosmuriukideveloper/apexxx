@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Knowledge <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Hub</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Discover a vast collection of academic resources, study materials, and expert-curated content to accelerate your learning journey.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <div class="relative">
                        <input type="text" placeholder="Search resources..." class="w-full sm:w-96 px-6 py-4 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-lg">
                        <button class="absolute right-2 top-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white p-2 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                            <i data-lucide="search" class="h-5 w-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>All Subjects</option>
                        <option>Mathematics</option>
                        <option>Science</option>
                        <option>Literature</option>
                        <option>History</option>
                        <option>Computer Science</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>All Types</option>
                        @foreach($types as $key => $type)
                            <option value="{{ $key }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>All Levels</option>
                        <option>Beginner</option>
                        <option>Intermediate</option>
                        <option>Advanced</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Sort by:</span>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Most Recent</option>
                        <option>Most Popular</option>
                        <option>Highest Rated</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Resources -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Resources</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Hand-picked by our experts to help you excel in your studies</p>
            </div>
            
<<<<<<< HEAD
            <!-- Courses Section -->
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold">Featured Courses</h2>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-700">View All →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($courses as $course)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 group">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 rounded-t-xl relative overflow-hidden">
                            @if($course->thumbnail_path)
                                <img src="{{ Storage::url($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute top-4 right-4">
                                <span class="bg-white px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>
                            @if($course->is_featured)
                            <div class="absolute top-4 left-4">
                                <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-bold">
                                    <i data-lucide="star" class="h-3 w-3 inline"></i> Featured
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst($course->category) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold mb-2 group-hover:text-blue-600 transition-colors">{{ $course->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->short_description, 100) }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm text-gray-500">By {{ $course->creator->user->name ?? 'Expert' }}</span>
                                <div class="flex items-center">
                                    <i data-lucide="star" class="h-4 w-4 text-yellow-400 fill-current"></i>
                                    <span class="ml-1 text-sm font-semibold">{{ number_format($course->average_rating ?? 0, 1) }}</span>
                                    <span class="text-xs text-gray-500 ml-1">({{ $course->total_reviews ?? 0 }})</span>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <i data-lucide="users" class="h-4 w-4 mr-1"></i>
                                <span>{{ $course->total_enrollments ?? 0 }} enrolled</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($course->discount_price)
                                    <span class="text-gray-500 line-through text-sm">${{ number_format($course->price, 2) }}</span>
                                    <span class="text-2xl font-bold text-green-600">${{ number_format($course->discount_price, 2) }}</span>
                                    @else
                                    <span class="text-2xl font-bold">${{ number_format($course->price, 2) }}</span>
                                    @endif
                                </div>
                                @auth
                                    <a href="{{ route('knowledge-resources.show', ['id' => $course->id, 'type' => 'course']) }}" 
                                       class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                        View Course
                                    </a>
                                @else
                                    <button onclick="showLoginPrompt()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                        View Course
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i data-lucide="inbox" class="h-16 w-16 mx-auto"></i>
                        </div>
                        <p class="text-gray-500 text-lg">No courses available yet.</p>
                        <p class="text-gray-400 text-sm mt-2">Check back soon for new content!</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Study Resources Section -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold">Study Resources</h2>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-700">View All →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    @forelse($studyResources as $resource)
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all group">
                        <div class="h-32 bg-gradient-to-br from-purple-400 to-pink-500 rounded-t-xl flex items-center justify-center relative overflow-hidden">
                            @if($resource->thumbnail_path)
                                <img src="{{ Storage::url($resource->thumbnail_path) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="file-text" class="h-12 w-12 text-white"></i>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="bg-white/90 text-purple-800 px-2 py-1 rounded text-xs font-semibold">
                                    {{ strtoupper($resource->resource_type) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold mb-2 group-hover:text-purple-600 transition-colors">{{ $resource->title }}</h4>
                            <div class="flex items-center text-xs text-gray-600 mb-3">
                                <i data-lucide="download" class="h-3 w-3 mr-1"></i>
                                <span>{{ $resource->download_count ?? 0 }} downloads</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($resource->is_free)
                                    <span class="text-lg font-bold text-green-600">FREE</span>
                                    @else
                                    <span class="text-lg font-bold">${{ number_format($resource->price, 2) }}</span>
                                    @endif
                                </div>
                                @auth
                                    <a href="{{ route('knowledge-resources.show', ['id' => $resource->id, 'type' => 'resource']) }}" 
                                       class="text-blue-600 text-sm font-medium hover:text-blue-700">
                                        View →
                                    </a>
                                @else
                                    <button onclick="showLoginPrompt()" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                                        View →
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-4 text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i data-lucide="inbox" class="h-16 w-16 mx-auto"></i>
                        </div>
                        <p class="text-gray-500 text-lg">No resources available yet.</p>
                        <p class="text-gray-400 text-sm mt-2">New study materials coming soon!</p>
                    </div>
                    @endforelse
                </div>
=======
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sample Resource Cards -->
                @for($i = 1; $i <= 6; $i++)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                            <i data-lucide="file-text" class="h-16 w-16 text-white"></i>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 text-blue-600 px-2 py-1 rounded-full text-xs font-semibold">Premium</span>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-black/50 text-white px-2 py-1 rounded text-xs">PDF • 2.5 MB</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">Mathematics</span>
                            <div class="flex items-center gap-1">
                                @for($j = 1; $j <= 5; $j++)
                                    <i data-lucide="star" class="h-3 w-3 {{ $j <= 4 ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="text-xs text-gray-600 ml-1">(4.8)</span>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                            Advanced Calculus Study Guide {{ $i }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            Comprehensive guide covering differential and integral calculus with practical examples and exercises.
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face" alt="Expert" class="w-6 h-6 rounded-full">
                                <span class="text-sm text-gray-600">Dr. Smith</span>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-900">${{ 19.99 }}</div>
                                <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Browse by Category</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Explore resources organized by subject and academic level</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @php
                    $categories = [
                        ['name' => 'Mathematics', 'icon' => 'calculator', 'count' => 245],
                        ['name' => 'Science', 'icon' => 'atom', 'count' => 189],
                        ['name' => 'Literature', 'icon' => 'book-open', 'count' => 156],
                        ['name' => 'History', 'icon' => 'scroll', 'count' => 134],
                        ['name' => 'Computer Science', 'icon' => 'code', 'count' => 198],
                        ['name' => 'Business', 'icon' => 'briefcase', 'count' => 167]
                    ];
                @endphp
                
                @foreach($categories as $category)
                <div class="bg-white rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 cursor-pointer group">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="{{ $category['icon'] }}" class="h-6 w-6 text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $category['name'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $category['count'] }} resources</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-4xl font-bold mb-2">1,200+</div>
                    <div class="text-blue-100">Resources Available</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">50+</div>
                    <div class="text-blue-100">Expert Contributors</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">10,000+</div>
                    <div class="text-blue-100">Downloads</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">4.9</div>
                    <div class="text-blue-100">Average Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Can't Find What You're Looking For?</h2>
            <p class="text-gray-600 mb-8">Our experts can create custom resources tailored to your specific needs.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                    Request Custom Resource
                </a>
                <a href="{{ route('experts') }}" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-medium hover:bg-gray-50 transition-all duration-200">
                    Browse Experts
                </a>
            </div>
        </div>
    </section>
</div>

<<<<<<< HEAD
<!-- Login Prompt Modal -->
<div id="loginModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-md mx-4 animate-fade-in">
        <h3 class="text-2xl font-bold mb-4">Login Required</h3>
        <p class="text-gray-600 mb-6">Please login to view resource details and make purchases.</p>
        <div class="flex gap-4">
            <a href="{{ route('login') }}" class="flex-1 bg-blue-600 text-white py-3 rounded-lg text-center font-semibold hover:bg-blue-700">
                Login
            </a>
            <a href="{{ route('register') }}" class="flex-1 border border-gray-300 py-3 rounded-lg text-center font-semibold hover:bg-gray-50">
                Register
            </a>
        </div>
        <button onclick="hideLoginPrompt()" class="w-full mt-4 text-gray-600 hover:text-gray-800">
            Cancel
        </button>
    </div>
</div>

=======
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
<<<<<<< HEAD

function showLoginPrompt() {
    document.getElementById('loginModal').classList.remove('hidden');
}

function hideLoginPrompt() {
    document.getElementById('loginModal').classList.add('hidden');
}
=======
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
</script>
@endsection
