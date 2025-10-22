@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 py-20 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-20 right-20 w-96 h-96 bg-orange-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-20 left-20 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold mb-6">
                Achieve Your Best Score
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                Test Prep <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600">Excellence</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                Comprehensive test preparation for SAT, ACT, GRE, GMAT, TOEFL, IELTS, and more. 
                Expert tutors, proven strategies, guaranteed score improvement.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-orange-600 to-amber-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-orange-700 hover:to-amber-700 transition-all transform hover:scale-105 shadow-lg">
                    Start Prep Today
                    <i data-lucide="zap" class="ml-2 h-5 w-5 group-hover:scale-110 transition-transform"></i>
                </a>
                <a href="#tests" class="inline-flex items-center justify-center border-2 border-orange-600 text-orange-600 px-8 py-4 rounded-xl font-semibold hover:bg-orange-50 transition-all">
                    Browse Tests
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Supported Tests -->
<section id="tests" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Tests We Cover</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Specialized preparation for all major standardized tests
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $tests = [
                    ['name' => 'SAT', 'full' => 'Scholastic Assessment Test', 'icon' => 'graduation-cap', 'students' => '500+', 'avg_improvement' => '+150 pts', 'color' => 'blue'],
                    ['name' => 'ACT', 'full' => 'American College Testing', 'icon' => 'book-open', 'students' => '400+', 'avg_improvement' => '+4 pts', 'color' => 'green'],
                    ['name' => 'GRE', 'full' => 'Graduate Record Examination', 'icon' => 'award', 'students' => '300+', 'avg_improvement' => '+12 pts', 'color' => 'purple'],
                    ['name' => 'GMAT', 'full' => 'Graduate Management Admission Test', 'icon' => 'briefcase', 'students' => '250+', 'avg_improvement' => '+50 pts', 'color' => 'indigo'],
                    ['name' => 'TOEFL', 'full' => 'Test of English as a Foreign Language', 'icon' => 'globe', 'students' => '600+', 'avg_improvement' => '+15 pts', 'color' => 'teal'],
                    ['name' => 'IELTS', 'full' => 'International English Language Testing System', 'icon' => 'message-circle', 'students' => '550+', 'avg_improvement' => '+1.5 band', 'color' => 'cyan'],
                    ['name' => 'MCAT', 'full' => 'Medical College Admission Test', 'icon' => 'heart-pulse', 'students' => '200+', 'avg_improvement' => '+8 pts', 'color' => 'red'],
                    ['name' => 'LSAT', 'full' => 'Law School Admission Test', 'icon' => 'scale', 'students' => '180+', 'avg_improvement' => '+10 pts', 'color' => 'amber'],
                    ['name' => 'AP Exams', 'full' => 'Advanced Placement Exams', 'icon' => 'star', 'students' => '700+', 'avg_improvement' => '4.2 avg', 'color' => 'orange'],
                ];
            @endphp

            @foreach($tests as $test)
            <div class="group bg-gradient-to-br from-{{ $test['color'] }}-50 to-white rounded-2xl p-6 border border-{{ $test['color'] }}-200 hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-{{ $test['color'] }}-500 to-{{ $test['color'] }}-600 rounded-xl flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                        <i data-lucide="{{ $test['icon'] }}" class="h-7 w-7"></i>
                    </div>
                    <span class="bg-{{ $test['color'] }}-100 text-{{ $test['color'] }}-800 px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $test['students'] }} students
                    </span>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $test['name'] }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ $test['full'] }}</p>
                
                <div class="bg-{{ $test['color'] }}-50 rounded-xl p-4 mb-4">
                    <div class="text-sm text-gray-600 mb-1">Avg. Score Improvement</div>
                    <div class="text-2xl font-bold text-{{ $test['color'] }}-600">{{ $test['avg_improvement'] }}</div>
                </div>
                
                <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-{{ $test['color'] }}-600 to-{{ $test['color'] }}-700 text-white text-center py-3 rounded-xl font-semibold hover:from-{{ $test['color'] }}-700 hover:to-{{ $test['color'] }}-800 transition-all">
                    Start Prep
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Study Plans -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-orange-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Personalized Study Plans</h2>
            <p class="text-xl text-gray-600">Tailored to your timeline and target score</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @php
                $plans = [
                    [
                        'name' => 'Intensive',
                        'duration' => '4-6 Weeks',
                        'hours' => '40-50 hours',
                        'sessions' => '3-4 sessions/week',
                        'ideal' => 'Last-minute prep',
                        'features' => ['Daily practice tests', 'Intensive drilling', 'Priority support', 'Crash course materials'],
                        'color' => 'red'
                    ],
                    [
                        'name' => 'Standard',
                        'duration' => '8-12 Weeks',
                        'hours' => '60-80 hours',
                        'sessions' => '2-3 sessions/week',
                        'ideal' => 'Most students',
                        'features' => ['Comprehensive coverage', 'Regular practice tests', 'Strategy sessions', 'Progress tracking'],
                        'color' => 'orange',
                        'popular' => true
                    ],
                    [
                        'name' => 'Extended',
                        'duration' => '16-20 Weeks',
                        'hours' => '100+ hours',
                        'sessions' => '2 sessions/week',
                        'ideal' => 'Maximum improvement',
                        'features' => ['Deep concept mastery', 'Weekly assessments', 'Flexible pacing', 'College counseling'],
                        'color' => 'amber'
                    ],
                ];
            @endphp

            @foreach($plans as $plan)
            <div class="relative bg-white rounded-2xl shadow-xl p-8 {{ isset($plan['popular']) ? 'ring-4 ring-orange-500 transform scale-105' : '' }}">
                @if(isset($plan['popular']))
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-gradient-to-r from-orange-600 to-amber-600 text-white px-6 py-2 rounded-full text-sm font-bold">
                        Most Popular
                    </span>
                </div>
                @endif

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan['name'] }} Plan</h3>
                    <div class="text-4xl font-bold text-{{ $plan['color'] }}-600 mb-2">{{ $plan['duration'] }}</div>
                    <p class="text-gray-600">{{ $plan['hours'] }} total</p>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="calendar" class="h-5 w-5 text-{{ $plan['color'] }}-600 mr-3"></i>
                        <span>{{ $plan['sessions'] }}</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="target" class="h-5 w-5 text-{{ $plan['color'] }}-600 mr-3"></i>
                        <span>{{ $plan['ideal'] }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">What's Included:</h4>
                    <ul class="space-y-2">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-center text-sm">
                            <i data-lucide="check-circle" class="h-4 w-4 text-green-600 mr-2"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-{{ $plan['color'] }}-600 to-{{ $plan['color'] }}-700 text-white text-center py-3 rounded-xl font-semibold hover:from-{{ $plan['color'] }}-700 hover:to-{{ $plan['color'] }}-800 transition-all">
                    Choose Plan
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Practice Materials -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Comprehensive Practice Materials</h2>
            <p class="text-xl text-gray-600">Everything you need to succeed</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $materials = [
                    ['title' => 'Practice Tests', 'count' => '20+', 'desc' => 'Full-length official practice tests', 'icon' => 'clipboard-check', 'color' => 'blue'],
                    ['title' => 'Question Bank', 'count' => '5,000+', 'desc' => 'Questions by topic & difficulty', 'icon' => 'database', 'color' => 'green'],
                    ['title' => 'Video Lessons', 'count' => '150+', 'desc' => 'Concept explanations & strategies', 'icon' => 'video', 'color' => 'purple'],
                    ['title' => 'Study Guides', 'count' => '50+', 'desc' => 'Comprehensive topic reviews', 'icon' => 'book', 'color' => 'orange'],
                ];
            @endphp

            @foreach($materials as $material)
            <div class="bg-gradient-to-br from-{{ $material['color'] }}-50 to-white rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-2xl">
                <div class="w-16 h-16 bg-gradient-to-br from-{{ $material['color'] }}-500 to-{{ $material['color'] }}-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="{{ $material['icon'] }}" class="h-8 w-8 text-white"></i>
                </div>
                <div class="text-3xl font-bold text-{{ $material['color'] }}-600 mb-2">{{ $material['count'] }}</div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $material['title'] }}</h3>
                <p class="text-sm text-gray-600">{{ $material['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Success Rates -->
<section class="py-20 bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Proven Results</h2>
            <p class="text-xl text-orange-100">Our students achieve exceptional scores</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @php
                $stats = [
                    ['value' => '95%', 'label' => 'Students improve their scores'],
                    ['value' => '4.8/5', 'label' => 'Average student rating'],
                    ['value' => '3,000+', 'label' => 'Students prepared annually'],
                    ['value' => '150+', 'label' => 'Average score increase'],
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="text-center">
                <div class="text-5xl font-bold text-white mb-2">{{ $stat['value'] }}</div>
                <div class="text-orange-100">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Pricing Comparison -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Test Prep Pricing</h2>
            <p class="text-xl text-gray-600">Flexible options for every budget</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow-2xl rounded-2xl overflow-hidden">
                <thead class="bg-gradient-to-r from-orange-600 to-amber-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">Test Type</th>
                        <th class="px-6 py-4 text-center">Hourly Rate</th>
                        <th class="px-6 py-4 text-center">10-Hour Package</th>
                        <th class="px-6 py-4 text-center">20-Hour Package</th>
                        <th class="px-6 py-4 text-center">Full Course</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $testPricing = [
                            ['test' => 'SAT/ACT', 'hourly' => '50', 'package10' => '450', 'package20' => '850', 'full' => '1,500'],
                            ['test' => 'GRE/GMAT', 'hourly' => '60', 'package10' => '550', 'package20' => '1,050', 'full' => '1,800'],
                            ['test' => 'TOEFL/IELTS', 'hourly' => '45', 'package10' => '400', 'package20' => '750', 'full' => '1,300'],
                            ['test' => 'MCAT/LSAT', 'hourly' => '70', 'package10' => '650', 'package20' => '1,250', 'full' => '2,200'],
                        ];
                    @endphp

                    @foreach($testPricing as $index => $price)
                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="px-6 py-4 font-semibold">{{ $price['test'] }}</td>
                        <td class="px-6 py-4 text-center">${{ $price['hourly'] }}/hr</td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-bold">${{ $price['package10'] }}</div>
                            <div class="text-xs text-green-600">Save 10%</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-bold">${{ $price['package20'] }}</div>
                            <div class="text-xs text-green-600">Save 15%</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-bold">${{ $price['full'] }}</div>
                            <div class="text-xs text-green-600">Best Value</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl p-12 border-2 border-orange-200">
            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="target" class="h-10 w-10 text-white"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Ready to Ace Your Test?</h2>
            <p class="text-xl text-gray-600 mb-8">
                Start your journey to test success with expert guidance and proven strategies
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center bg-gradient-to-r from-orange-600 to-amber-600 text-white px-10 py-5 rounded-xl font-bold text-lg hover:from-orange-700 hover:to-amber-700 transition-all transform hover:scale-105 shadow-2xl">
                Begin Test Prep
                <i data-lucide="arrow-right" class="ml-3 h-6 w-6"></i>
            </a>
            <p class="mt-6 text-gray-600">
                <i data-lucide="shield-check" class="inline h-5 w-5 mr-2"></i>
                Score improvement guarantee • Free diagnostic test • Flexible scheduling
            </p>
        </div>
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

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
