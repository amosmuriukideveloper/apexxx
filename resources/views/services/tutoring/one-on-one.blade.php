@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-72 h-72 bg-green-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-teal-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold mb-6">
                Personalized Learning Experience
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                One-on-One <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-teal-600">Tutoring</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                Connect with expert tutors for personalized, one-on-one sessions tailored to your learning style and goals. 
                Available 24/7 across all subjects.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-teal-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-green-700 hover:to-teal-700 transition-all transform hover:scale-105 shadow-lg">
                    Find Your Tutor
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#subjects" class="inline-flex items-center justify-center border-2 border-green-600 text-green-600 px-8 py-4 rounded-xl font-semibold hover:bg-green-50 transition-all">
                    Browse Subjects
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Subject Selection with Icons -->
<section id="subjects" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Subject</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Expert tutors available in over 50+ subjects
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @php
                $subjects = [
                    ['name' => 'Mathematics', 'icon' => 'calculator', 'tutors' => 120, 'color' => 'blue'],
                    ['name' => 'Physics', 'icon' => 'atom', 'tutors' => 85, 'color' => 'purple'],
                    ['name' => 'Chemistry', 'icon' => 'flask-conical', 'tutors' => 78, 'color' => 'green'],
                    ['name' => 'Biology', 'icon' => 'dna', 'tutors' => 92, 'color' => 'emerald'],
                    ['name' => 'English', 'icon' => 'book-open', 'tutors' => 105, 'color' => 'red'],
                    ['name' => 'History', 'icon' => 'scroll', 'tutors' => 68, 'color' => 'amber'],
                    ['name' => 'Computer Science', 'icon' => 'code', 'tutors' => 95, 'color' => 'indigo'],
                    ['name' => 'Economics', 'icon' => 'trending-up', 'tutors' => 73, 'color' => 'yellow'],
                    ['name' => 'Languages', 'icon' => 'globe', 'tutors' => 110, 'color' => 'pink'],
                    ['name' => 'Statistics', 'icon' => 'bar-chart', 'tutors' => 65, 'color' => 'teal'],
                    ['name' => 'Engineering', 'icon' => 'wrench', 'tutors' => 58, 'color' => 'orange'],
                    ['name' => 'Business', 'icon' => 'briefcase', 'tutors' => 82, 'color' => 'cyan'],
                ];
            @endphp

            @foreach($subjects as $subject)
            <div class="group bg-gradient-to-br from-{{ $subject['color'] }}-50 to-white rounded-2xl p-6 border border-{{ $subject['color'] }}-200 hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 cursor-pointer">
                <div class="w-14 h-14 bg-gradient-to-br from-{{ $subject['color'] }}-500 to-{{ $subject['color'] }}-600 rounded-xl flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $subject['icon'] }}" class="h-7 w-7"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">{{ $subject['name'] }}</h3>
                <p class="text-sm text-gray-600">{{ $subject['tutors'] }} tutors</p>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <p class="text-gray-600 mb-4">Don't see your subject?</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700">
                Request a specialized tutor
                <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
            </a>
        </div>
    </div>
</section>

<!-- Tutor Matching System Preview -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Smart Tutor Matching</h2>
            <p class="text-xl text-gray-600">Our AI-powered system finds the perfect tutor for you</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="space-y-6">
                    @php
                        $matchingSteps = [
                            ['title' => 'Tell us your needs', 'desc' => 'Subject, level, learning goals, and availability', 'icon' => 'user', 'color' => 'green'],
                            ['title' => 'AI analyzes profiles', 'desc' => 'Matches expertise, teaching style, and schedule', 'icon' => 'cpu', 'color' => 'teal'],
                            ['title' => 'Review recommendations', 'desc' => 'See profiles, ratings, and sample videos', 'icon' => 'eye', 'color' => 'emerald'],
                            ['title' => 'Book your session', 'desc' => 'Choose time, confirm booking, and start learning', 'icon' => 'calendar-check', 'color' => 'cyan'],
                        ];
                    @endphp

                    @foreach($matchingSteps as $index => $step)
                    <div class="flex items-start gap-4 group">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-{{ $step['color'] }}-500 to-{{ $step['color'] }}-600 rounded-xl flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <i data-lucide="{{ $step['icon'] }}" class="h-7 w-7"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $index + 1 }}. {{ $step['title'] }}</h3>
                            <p class="text-gray-600">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="sparkles" class="h-10 w-10 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Find Your Perfect Match</h3>
                    <p class="text-gray-600">Average matching accuracy: 96%</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Teaching Style Match</span>
                            <span class="text-sm font-bold text-green-600">98%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full" style="width: 98%"></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Schedule Compatibility</span>
                            <span class="text-sm font-bold text-green-600">95%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Subject Expertise</span>
                            <span class="text-sm font-bold text-green-600">100%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tutor Profiles with Ratings -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Meet Our Top Tutors</h2>
            <p class="text-xl text-gray-600">Highly rated by thousands of students</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for($i = 1; $i <= 3; $i++)
            <div class="bg-gradient-to-br from-white to-green-50 rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center relative">
                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-green-600">
                        T{{ $i }}
                    </div>
                    <div class="absolute top-4 right-4 bg-white rounded-full px-3 py-1 text-sm font-semibold text-green-600">
                        <i data-lucide="award" class="h-4 w-4 inline mr-1"></i>Top Rated
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Tutor {{ chr(64 + $i) }}{{ $i }}</h3>
                    <p class="text-green-600 font-semibold mb-1">Mathematics & Physics</p>
                    <p class="text-sm text-gray-600 mb-4">PhD, 12 years experience</p>
                    
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center">
                            @for($j = 1; $j <= 5; $j++)
                                <i data-lucide="star" class="h-4 w-4 text-yellow-400 fill-current"></i>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold text-gray-700">(5.0 • 350 reviews)</span>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm text-gray-600">
                            <i data-lucide="check-circle" class="h-4 w-4 text-green-600 mr-2"></i>
                            1,200+ hours taught
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i data-lucide="clock" class="h-4 w-4 text-green-600 mr-2"></i>
                            Usually responds in 1 hour
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i data-lucide="video" class="h-4 w-4 text-green-600 mr-2"></i>
                            Online sessions available
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-3xl font-bold text-gray-900">$35</div>
                            <div class="text-sm text-gray-600">per hour</div>
                        </div>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transition-all">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Pricing Tiers -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Flexible Pricing</h2>
            <p class="text-xl text-gray-600">Choose the plan that fits your learning needs</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $plans = [
                    ['name' => 'Pay As You Go', 'price' => '40', 'desc' => 'Single session', 'features' => ['$40 per hour', 'No commitment', 'Cancel anytime', 'All subjects']],
                    ['name' => '10 Hour Package', 'price' => '35', 'desc' => 'Save 12%', 'features' => ['$35 per hour', '10 hours total', '3 month validity', 'Priority booking'], 'popular' => true],
                    ['name' => '20 Hour Package', 'price' => '30', 'desc' => 'Save 25%', 'features' => ['$30 per hour', '20 hours total', '6 month validity', 'VIP support']],
                ];
            @endphp

            @foreach($plans as $plan)
            <div class="relative bg-white rounded-2xl shadow-xl p-8 {{ isset($plan['popular']) ? 'ring-4 ring-green-500 transform scale-105' : '' }}">
                @if(isset($plan['popular']))
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-2 rounded-full text-sm font-bold">
                        Best Value
                    </span>
                </div>
                @endif

                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
                    <div class="text-5xl font-bold text-gray-900 mb-2">${{ $plan['price'] }}</div>
                    <p class="text-gray-600">{{ $plan['desc'] }}</p>
                </div>

                <ul class="space-y-4 mb-8">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-center">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-green-600 to-teal-600 text-white text-center py-4 rounded-xl font-semibold hover:from-green-700 hover:to-teal-700 transition-all">
                    Get Started
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Book Trial Session CTA -->
<section class="py-20 bg-gradient-to-r from-green-600 via-teal-600 to-emerald-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-12 border border-white/20">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="user-check" class="h-12 w-12 text-green-600"></i>
            </div>
            <h2 class="text-4xl font-bold text-white mb-6">Try Your First Session Free</h2>
            <p class="text-xl text-green-100 mb-8 leading-relaxed">
                Not sure if one-on-one tutoring is right for you? Get a complimentary 30-minute trial session with no commitment.
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center bg-white text-green-600 px-10 py-5 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
                Book Free Trial Session
                <i data-lucide="arrow-right" class="ml-3 h-6 w-6"></i>
            </a>
            <p class="mt-6 text-green-100 text-sm">
                <i data-lucide="clock" class="inline h-5 w-5 mr-2"></i>
                30 minutes • No payment required • Meet your tutor
            </p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<style>
.animation-delay-2000 {
    animation-delay: 2s;
}
</style>
@endsection
