@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-green-50 via-teal-50 to-blue-50 py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72 bg-green-300 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
        <div class="absolute top-20 right-10 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold mb-6 animate-fade-in">
                Academic Research Excellence
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6 animate-slide-up">
                Research Papers <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-teal-600">That Get Results</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                In-depth academic research with proper methodology, citations, and analysis. 
                Written by PhD researchers with expertise in your field.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-teal-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-green-700 hover:to-teal-700 transition-all transform hover:scale-105 shadow-lg">
                    Order Research Paper
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#methodology" class="inline-flex items-center justify-center border-2 border-green-600 text-green-600 px-8 py-4 rounded-xl font-semibold hover:bg-green-50 transition-all">
                    Our Methodology
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Research Methodology Overview -->
<section id="methodology" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Research Methodology</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Rigorous academic standards meet innovative research techniques
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="space-y-6">
                    @php
                        $methodSteps = [
                            ['title' => 'Topic Analysis', 'desc' => 'Deep dive into your research question and objectives', 'icon' => 'search'],
                            ['title' => 'Literature Review', 'desc' => 'Comprehensive review of existing scholarly work', 'icon' => 'book-open'],
                            ['title' => 'Data Collection', 'desc' => 'Systematic gathering of relevant research data', 'icon' => 'database'],
                            ['title' => 'Analysis & Synthesis', 'desc' => 'Critical examination and interpretation of findings', 'icon' => 'bar-chart'],
                            ['title' => 'Writing & Citations', 'desc' => 'Academic writing with proper referencing', 'icon' => 'pen-tool'],
                        ];
                    @endphp

                    @foreach($methodSteps as $index => $step)
                    <div class="flex items-start gap-4 group">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-teal-500 rounded-xl flex items-center justify-center text-white font-bold group-hover:scale-110 transition-transform">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                            <p class="text-gray-600">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl p-8">
                <div class="aspect-square bg-white rounded-xl shadow-xl flex items-center justify-center">
                    <i data-lucide="clipboard-check" class="h-48 w-48 text-green-600 opacity-20"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Citation Styles -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">All Citation Styles Supported</h2>
            <p class="text-xl text-gray-600">Expert formatting in any citation style</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @php
                $citationStyles = ['APA', 'MLA', 'Chicago', 'Harvard', 'IEEE', 'Vancouver', 'Turabian', 'AMA', 'ASA', 'OSCOLA', 'MHRA', 'ACS'];
            @endphp

            @foreach($citationStyles as $style)
            <div class="bg-white rounded-xl p-6 text-center shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="quote" class="h-8 w-8 text-green-600"></i>
                </div>
                <div class="font-bold text-gray-900">{{ $style }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Quality Assurance Process -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Quality Assurance Process</h2>
            <p class="text-xl text-gray-600">Multiple checks ensure excellence</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $qaSteps = [
                    ['title' => 'Expert Writing', 'desc' => 'PhD researchers write your paper', 'icon' => 'user-check', 'color' => 'green'],
                    ['title' => 'Quality Review', 'desc' => 'Senior editors review content', 'icon' => 'check-circle', 'color' => 'teal'],
                    ['title' => 'Plagiarism Check', 'desc' => 'Turnitin verification included', 'icon' => 'shield-check', 'color' => 'blue'],
                    ['title' => 'Final Proofreading', 'desc' => 'Professional editing & formatting', 'icon' => 'eye', 'color' => 'purple'],
                ];
            @endphp

            @foreach($qaSteps as $qa)
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-{{ $qa['color'] }}-400 to-{{ $qa['color'] }}-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition-opacity"></div>
                <div class="relative bg-white rounded-2xl p-6 text-center transform group-hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-{{ $qa['color'] }}-500 to-{{ $qa['color'] }}-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="{{ $qa['icon'] }}" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $qa['title'] }}</h3>
                    <p class="text-gray-600">{{ $qa['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Sample Papers Showcase -->
<section class="py-20 bg-gradient-to-br from-green-50 to-teal-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Sample Research Papers</h2>
            <p class="text-xl text-gray-600">Browse examples of our work across disciplines</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $samples = [
                    ['title' => 'Climate Change Impact on Agriculture', 'subject' => 'Environmental Science', 'pages' => 15, 'grade' => 'A+'],
                    ['title' => 'Machine Learning in Healthcare', 'subject' => 'Computer Science', 'pages' => 20, 'grade' => 'A'],
                    ['title' => 'Economic Effects of Globalization', 'subject' => 'Economics', 'pages' => 18, 'grade' => 'A+'],
                ];
            @endphp

            @foreach($samples as $sample)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden group hover:shadow-2xl transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center relative overflow-hidden">
                    <i data-lucide="file-text" class="h-24 w-24 text-white opacity-50"></i>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="bg-white/90 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $sample['subject'] }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">
                        {{ $sample['title'] }}
                    </h3>
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                        <span><i data-lucide="file" class="h-4 w-4 inline mr-1"></i>{{ $sample['pages'] }} pages</span>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">{{ $sample['grade'] }}</span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transition-all">
                        Preview Sample
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Animated Process Flow -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">From Order to Delivery</h2>
            <p class="text-xl text-gray-600">Track your research paper every step of the way</p>
        </div>

        <div class="relative">
            <!-- Animated connection line -->
            <svg class="absolute top-0 left-0 w-full h-full" style="z-index: 0;">
                <path id="connectionPath" d="M 100,100 Q 300,50 500,100 T 900,100" stroke="#10b981" stroke-width="3" fill="none" stroke-dasharray="10,5" class="animate-dash"/>
            </svg>

            <div class="relative grid grid-cols-1 md:grid-cols-5 gap-6" style="z-index: 1;">
                @php
                    $flowSteps = [
                        ['icon' => 'shopping-cart', 'title' => 'Place Order', 'time' => '5 min'],
                        ['icon' => 'user-plus', 'title' => 'Writer Match', 'time' => '30 min'],
                        ['icon' => 'edit', 'title' => 'Research', 'time' => '3-7 days'],
                        ['icon' => 'check-square', 'title' => 'QA Check', 'time' => '1 day'],
                        ['icon' => 'download', 'title' => 'Delivery', 'time' => 'On time'],
                    ];
                @endphp

                @foreach($flowSteps as $index => $step)
                <div class="bg-gradient-to-br from-white to-green-50 rounded-2xl p-6 shadow-lg transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <i data-lucide="{{ $step['icon'] }}" class="h-8 w-8 text-white"></i>
                    </div>
                    <div class="text-center">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $step['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Pricing Tiers -->
<section class="py-20 bg-gradient-to-br from-gray-900 to-green-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Research Paper Pricing</h2>
            <p class="text-xl text-green-100">Transparent pricing based on academic level</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $pricingTiers = [
                    ['level' => 'High School', 'price' => '18', 'features' => ['Basic research', 'Standard quality', '5 sources minimum']],
                    ['level' => 'Undergraduate', 'price' => '23', 'features' => ['Detailed research', 'High quality', '10+ sources'], 'popular' => true],
                    ['level' => 'Graduate', 'price' => '28', 'features' => ['Advanced research', 'Premium quality', '15+ sources']],
                    ['level' => 'PhD', 'price' => '35', 'features' => ['Expert research', 'Publication quality', '20+ sources']],
                ];
            @endphp

            @foreach($pricingTiers as $tier)
            <div class="relative bg-white text-gray-900 rounded-2xl p-8 {{ isset($tier['popular']) ? 'ring-4 ring-green-400 transform scale-105' : '' }}">
                @if(isset($tier['popular']))
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                </div>
                @endif
                
                <div class="text-center">
                    <h3 class="text-xl font-bold mb-4">{{ $tier['level'] }}</h3>
                    <div class="text-5xl font-bold mb-2">${{ $tier['price'] }}</div>
                    <p class="text-gray-600 mb-6">per page</p>
                    
                    <ul class="space-y-3 mb-8 text-left">
                        @foreach($tier['features'] as $feature)
                        <li class="flex items-center">
                            <i data-lucide="check" class="h-5 w-5 text-green-600 mr-2"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    
                    <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transition-all">
                        Order Now
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-green-600 to-teal-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Start Your Research?</h2>
        <p class="text-xl text-green-100 mb-8">
            Get expert research assistance from PhD-qualified researchers
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center bg-white text-green-600 px-10 py-5 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
            Order Research Paper
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

@keyframes dash {
    to {
        stroke-dashoffset: -20;
    }
}

.animate-dash {
    animation: dash 20s linear infinite;
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
