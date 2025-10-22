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

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
