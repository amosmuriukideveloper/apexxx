@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-cyan-50 via-blue-50 to-indigo-50 py-20 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-20 right-20 w-96 h-96 bg-cyan-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float"></div>
        <div class="absolute bottom-20 left-20 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float animation-delay-3000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-cyan-100 text-cyan-800 rounded-full text-sm font-semibold mb-6">
                Comprehensive Learning Materials
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                Study <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-600">Guides</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                In-depth study guides with detailed explanations, practice questions, and exam strategies. 
                Master your subjects with structured learning paths created by academic experts.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('knowledge-resources.index') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg">
                    Explore Study Guides
                    <i data-lucide="book-open" class="ml-2 h-5 w-5 group-hover:scale-110 transition-transform"></i>
                </a>
                <a href="#features" class="inline-flex items-center justify-center border-2 border-cyan-600 text-cyan-600 px-8 py-4 rounded-xl font-semibold hover:bg-cyan-50 transition-all">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- What's Included -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">What's in Our Study Guides?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Everything you need for comprehensive understanding
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $contents = [
                    ['title' => 'Chapter Summaries', 'desc' => 'Concise overviews of key topics', 'icon' => 'file-text', 'color' => 'cyan'],
                    ['title' => 'Detailed Explanations', 'desc' => 'In-depth breakdowns of concepts', 'icon' => 'lightbulb', 'color' => 'blue'],
                    ['title' => 'Practice Questions', 'desc' => 'Test your knowledge with quizzes', 'icon' => 'help-circle', 'color' => 'indigo'],
                    ['title' => 'Solved Examples', 'desc' => 'Step-by-step problem solutions', 'icon' => 'check-square', 'color' => 'sky'],
                    ['title' => 'Exam Strategies', 'desc' => 'Tips to ace your tests', 'icon' => 'target', 'color' => 'violet'],
                    ['title' => 'Visual Diagrams', 'desc' => 'Charts and illustrations', 'icon' => 'image', 'color' => 'purple'],
                ];
            @endphp

            @foreach($contents as $content)
            <div class="group bg-gradient-to-br from-{{ $content['color'] }}-50 to-white rounded-2xl p-6 border border-{{ $content['color'] }}-200 hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-{{ $content['color'] }}-500 to-{{ $content['color'] }}-600 rounded-xl flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $content['icon'] }}" class="h-7 w-7"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $content['title'] }}</h3>
                <p class="text-gray-600">{{ $content['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Popular Guides -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Most Popular Study Guides</h2>
            <p class="text-xl text-gray-600">Top-rated by students worldwide</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $guides = [
                    ['title' => 'Calculus Mastery', 'subject' => 'Mathematics', 'pages' => 120, 'rating' => 4.9, 'price' => 29.99, 'color' => 'blue'],
                    ['title' => 'Organic Chemistry', 'subject' => 'Chemistry', 'pages' => 150, 'rating' => 4.8, 'price' => 34.99, 'color' => 'green'],
                    ['title' => 'Physics Complete', 'subject' => 'Physics', 'pages' => 180, 'rating' => 4.9, 'price' => 39.99, 'color' => 'purple'],
                    ['title' => 'Biology Essentials', 'subject' => 'Biology', 'pages' => 140, 'rating' => 4.7, 'price' => 32.99, 'color' => 'emerald'],
                ];
            @endphp

            @foreach($guides as $guide)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 group">
                <div class="h-48 bg-gradient-to-br from-{{ $guide['color'] }}-400 to-{{ $guide['color'] }}-600 flex items-center justify-center relative">
                    <i data-lucide="book-open" class="h-20 w-20 text-white opacity-50"></i>
                    <div class="absolute top-4 right-4">
                        <span class="bg-white/90 text-{{ $guide['color'] }}-800 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $guide['subject'] }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-cyan-600 transition-colors">
                        {{ $guide['title'] }}
                    </h3>
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <span><i data-lucide="file" class="h-4 w-4 inline mr-1"></i>{{ $guide['pages'] }} pages</span>
                        <span><i data-lucide="star" class="h-4 w-4 inline mr-1 text-yellow-400 fill-current"></i>{{ $guide['rating'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-900">${{ number_format($guide['price'], 2) }}</span>
                        <button class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all">
                            View Guide
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Study Levels -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Guides for Every Level</h2>
            <p class="text-xl text-gray-600">From high school to graduate studies</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @php
                $levels = [
                    ['level' => 'High School', 'count' => '200+', 'icon' => 'graduation-cap', 'color' => 'blue'],
                    ['level' => 'Undergraduate', 'count' => '300+', 'icon' => 'book', 'color' => 'cyan'],
                    ['level' => 'Graduate', 'count' => '150+', 'icon' => 'award', 'color' => 'indigo'],
                    ['level' => 'Professional', 'count' => '80+', 'icon' => 'briefcase', 'color' => 'purple'],
                ];
            @endphp

            @foreach($levels as $level)
            <div class="bg-gradient-to-br from-{{ $level['color'] }}-50 to-white rounded-2xl p-8 text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-{{ $level['color'] }}-500 to-{{ $level['color'] }}-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="{{ $level['icon'] }}" class="h-8 w-8 text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $level['level'] }}</h3>
                <p class="text-{{ $level['color'] }}-600 font-semibold text-lg">{{ $level['count'] }} guides</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="py-20 bg-gradient-to-r from-cyan-600 to-blue-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Why Students Love Our Guides</h2>
            <p class="text-xl text-cyan-100">Real results, proven success</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $benefits = [
                    ['stat' => '95%', 'desc' => 'Students improve grades'],
                    ['stat' => '4.8/5', 'desc' => 'Average rating'],
                    ['stat' => '50,000+', 'desc' => 'Guides downloaded'],
                ];
            @endphp

            @foreach($benefits as $benefit)
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 text-center border border-white/20 hover:bg-white/20 transition-all">
                <div class="text-5xl font-bold text-white mb-2">{{ $benefit['stat'] }}</div>
                <p class="text-cyan-100 text-lg">{{ $benefit['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How to Use -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">How to Get Started</h2>
            <p class="text-xl text-gray-600">Access premium study guides in minutes</p>
        </div>

        <div class="relative">
            <!-- Connection Line -->
            <div class="hidden md:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-cyan-200 via-blue-200 to-indigo-200 transform -translate-y-1/2"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                @php
                    $steps = [
                        ['num' => '1', 'title' => 'Browse', 'desc' => 'Find guides for your subject', 'icon' => 'search'],
                        ['num' => '2', 'title' => 'Preview', 'desc' => 'Check sample pages', 'icon' => 'eye'],
                        ['num' => '3', 'title' => 'Purchase', 'desc' => 'Secure instant checkout', 'icon' => 'shopping-cart'],
                        ['num' => '4', 'title' => 'Study', 'desc' => 'Download and learn', 'icon' => 'book-open'],
                    ];
                @endphp

                @foreach($steps as $step)
                <div class="relative bg-white">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative z-10 w-20 h-20 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-white mb-4 shadow-lg hover:scale-110 transition-transform">
                            <i data-lucide="{{ $step['icon'] }}" class="h-10 w-10"></i>
                        </div>
                        <div class="absolute top-10 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-6xl font-bold text-gray-100 z-0">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Pricing -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Flexible Pricing Options</h2>
            <p class="text-xl text-gray-600">Choose what works for you</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @php
                $packages = [
                    ['name' => 'Single Guide', 'price' => '19-39', 'features' => ['One complete guide', 'Lifetime access', 'Updates included', 'PDF format']],
                    ['name' => 'Subject Pack', 'price' => '99', 'features' => ['3-4 related guides', 'Save 20%', 'All formats', 'Priority support'], 'popular' => true],
                    ['name' => 'Unlimited', 'price' => '199', 'features' => ['All guides', 'All subjects', 'All levels', 'New releases']],
                ];
            @endphp

            @foreach($packages as $package)
            <div class="relative bg-white rounded-2xl shadow-xl p-8 {{ isset($package['popular']) ? 'ring-4 ring-cyan-500 transform scale-105' : '' }}">
                @if(isset($package['popular']))
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-cyan-500 text-white px-6 py-2 rounded-full text-sm font-bold">Best Value</span>
                </div>
                @endif

                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $package['name'] }}</h3>
                    <div class="text-5xl font-bold text-gray-900 mb-2">${{ $package['price'] }}</div>
                    <p class="text-gray-600">per guide/pack</p>
                </div>

                <ul class="space-y-4 mb-8">
                    @foreach($package['features'] as $feature)
                    <li class="flex items-center">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('knowledge-resources.index') }}" class="block w-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-center py-4 rounded-xl font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all">
                    Get Started
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-gradient-to-r from-cyan-600 to-blue-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Excel in Your Studies?</h2>
        <p class="text-xl text-cyan-100 mb-8">
            Join thousands of students achieving academic success with our study guides
        </p>
        <a href="{{ route('knowledge-resources.index') }}" class="inline-flex items-center bg-white text-cyan-600 px-10 py-5 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
            Browse All Study Guides
            <i data-lucide="arrow-right" class="ml-3 h-6 w-6"></i>
        </a>
    </div>
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animation-delay-3000 {
    animation-delay: 3s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
