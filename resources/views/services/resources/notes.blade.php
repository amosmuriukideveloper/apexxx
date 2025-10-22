@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 py-20 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold mb-6">
                Premium Study Materials
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                Study <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">Notes</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                Comprehensive, well-organized study notes created by top students and expert tutors. 
                Save time and study smarter with our high-quality materials.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('knowledge-resources.index') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105 shadow-lg">
                    Browse Study Notes
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#preview" class="inline-flex items-center justify-center border-2 border-purple-600 text-purple-600 px-8 py-4 rounded-xl font-semibold hover:bg-purple-50 transition-all">
                    View Samples
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Our Study Notes?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Quality materials that help you understand and retain key concepts
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $features = [
                    ['title' => 'Comprehensive', 'desc' => 'Cover all key topics and concepts thoroughly', 'icon' => 'book-open', 'color' => 'purple'],
                    ['title' => 'Well-Organized', 'desc' => 'Structured for easy navigation and review', 'icon' => 'layout', 'color' => 'pink'],
                    ['title' => 'Expert Created', 'desc' => 'Made by top students and educators', 'icon' => 'award', 'color' => 'rose'],
                    ['title' => 'Exam-Focused', 'desc' => 'Highlight what matters most for tests', 'icon' => 'target', 'color' => 'fuchsia'],
                ];
            @endphp

            @foreach($features as $feature)
            <div class="group text-center p-6 rounded-2xl bg-gradient-to-br from-{{ $feature['color'] }}-50 to-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $feature['icon'] }}" class="h-8 w-8 text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                <p class="text-gray-600">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Subject Coverage -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Subjects Covered</h2>
            <p class="text-xl text-gray-600">Study notes available across major academic disciplines</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @php
                $subjects = [
                    ['name' => 'Mathematics', 'icon' => 'calculator', 'count' => '150+'],
                    ['name' => 'Sciences', 'icon' => 'atom', 'count' => '200+'],
                    ['name' => 'Business', 'icon' => 'briefcase', 'count' => '120+'],
                    ['name' => 'Engineering', 'icon' => 'wrench', 'count' => '100+'],
                    ['name' => 'Languages', 'icon' => 'message-square', 'count' => '80+'],
                    ['name' => 'Humanities', 'icon' => 'book', 'count' => '90+'],
                ];
            @endphp

            @foreach($subjects as $subject)
            <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="{{ $subject['icon'] }}" class="h-7 w-7 text-white"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">{{ $subject['name'] }}</h3>
                <p class="text-sm text-gray-600">{{ $subject['count'] }} notes</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Sample Preview -->
<section id="preview" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Sample Study Notes</h2>
            <p class="text-xl text-gray-600">Get a preview of our high-quality materials</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for($i = 1; $i <= 3; $i++)
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 group">
                <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center relative">
                    <i data-lucide="file-text" class="h-20 w-20 text-white opacity-50"></i>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="bg-white/90 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                            SAMPLE {{ $i }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                        Advanced Calculus Notes
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Complete chapter summaries with formulas, examples, and practice problems.
                    </p>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span><i data-lucide="file" class="h-4 w-4 inline mr-1"></i>25 pages</span>
                        <span><i data-lucide="star" class="h-4 w-4 inline mr-1 text-yellow-400 fill-current"></i>4.9</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-purple-600">$12.99</span>
                        <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all">
                            Preview
                        </button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-20 bg-gradient-to-br from-purple-600 to-pink-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">How to Access Study Notes</h2>
            <p class="text-xl text-purple-100">Simple 3-step process</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $steps = [
                    ['num' => '1', 'title' => 'Browse & Select', 'desc' => 'Find notes for your subject'],
                    ['num' => '2', 'title' => 'Purchase', 'desc' => 'Secure checkout with instant access'],
                    ['num' => '3', 'title' => 'Download & Study', 'desc' => 'Access anytime, anywhere'],
                ];
            @endphp

            @foreach($steps as $step)
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 text-center border border-white/20 hover:bg-white/20 transition-all">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-purple-600">
                    {{ $step['num'] }}
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">{{ $step['title'] }}</h3>
                <p class="text-purple-100">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Pricing -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Affordable Pricing</h2>
            <p class="text-xl text-gray-600">Quality notes at student-friendly prices</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $pricing = [
                    ['type' => 'Chapter Notes', 'price' => '5-10', 'desc' => 'Single chapter coverage'],
                    ['type' => 'Unit Notes', 'price' => '15-25', 'desc' => 'Complete unit material', 'popular' => true],
                    ['type' => 'Course Notes', 'price' => '40-60', 'desc' => 'Full course coverage'],
                    ['type' => 'Bundle Packs', 'price' => '100+', 'desc' => 'Multiple courses'],
                ];
            @endphp

            @foreach($pricing as $plan)
            <div class="relative bg-gradient-to-br from-purple-50 to-white rounded-2xl p-6 text-center {{ isset($plan['popular']) ? 'ring-4 ring-purple-500 transform scale-105' : 'shadow-lg' }}">
                @if(isset($plan['popular']))
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-purple-500 text-white px-4 py-1 rounded-full text-xs font-bold">Popular</span>
                </div>
                @endif
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $plan['type'] }}</h3>
                <div class="text-4xl font-bold text-purple-600 mb-2">${{ $plan['price'] }}</div>
                <p class="text-gray-600 mb-6">{{ $plan['desc'] }}</p>
                <a href="{{ route('knowledge-resources.index') }}" class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all">
                    Browse Notes
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-gradient-to-r from-purple-600 to-pink-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Start Studying Smarter Today</h2>
        <p class="text-xl text-purple-100 mb-8">
            Access thousands of high-quality study notes
        </p>
        <a href="{{ route('knowledge-resources.index') }}" class="inline-flex items-center bg-white text-purple-600 px-10 py-5 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
            Browse All Study Notes
            <i data-lucide="arrow-right" class="ml-3 h-6 w-6"></i>
        </a>
    </div>
</section>

<style>
@keyframes blob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
