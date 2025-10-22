@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-20 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-20 left-20 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float animation-delay-4000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold mb-6">
                PhD-Level Academic Writing
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                Dissertation & <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Thesis Writing</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                Comprehensive support for your graduate research project. From proposal to defense, 
                we provide expert guidance every step of the way.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                    Get Started Today
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#chapters" class="inline-flex items-center justify-center border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-xl font-semibold hover:bg-indigo-50 transition-all">
                    View Our Process
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Chapter-by-Chapter Breakdown -->
<section id="chapters" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Chapter-by-Chapter Support</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Comprehensive assistance for every section of your dissertation
            </p>
        </div>

        <div class="space-y-6">
            @php
                $chapters = [
                    ['num' => '1', 'title' => 'Introduction', 'desc' => 'Background, problem statement, research questions, and objectives', 'icon' => 'play-circle', 'color' => 'indigo'],
                    ['num' => '2', 'title' => 'Literature Review', 'desc' => 'Comprehensive review of existing research and theoretical framework', 'icon' => 'book-open', 'color' => 'purple'],
                    ['num' => '3', 'title' => 'Methodology', 'desc' => 'Research design, methods, data collection, and analysis procedures', 'icon' => 'settings', 'color' => 'pink'],
                    ['num' => '4', 'title' => 'Results/Findings', 'desc' => 'Presentation of data, analysis, and interpretation of findings', 'icon' => 'bar-chart', 'color' => 'rose'],
                    ['num' => '5', 'title' => 'Discussion', 'desc' => 'Critical analysis, implications, and connections to literature', 'icon' => 'message-circle', 'color' => 'fuchsia'],
                    ['num' => '6', 'title' => 'Conclusion', 'desc' => 'Summary, contributions, limitations, and future research', 'icon' => 'flag', 'color' => 'violet'],
                ];
            @endphp

            @foreach($chapters as $index => $chapter)
            <div class="group bg-gradient-to-r from-{{ $chapter['color'] }}-50 to-white rounded-2xl p-6 border border-{{ $chapter['color'] }}-200 hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-{{ $chapter['color'] }}-500 to-{{ $chapter['color'] }}-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold group-hover:scale-110 transition-transform">
                        {{ $chapter['num'] }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-2xl font-bold text-gray-900">Chapter {{ $chapter['num'] }}: {{ $chapter['title'] }}</h3>
                            <i data-lucide="{{ $chapter['icon'] }}" class="h-8 w-8 text-{{ $chapter['color'] }}-600"></i>
                        </div>
                        <p class="text-gray-600 text-lg">{{ $chapter['desc'] }}</p>
                        <div class="mt-4 flex items-center text-sm text-{{ $chapter['color'] }}-600 font-semibold">
                            <i data-lucide="check-circle" class="h-4 w-4 mr-2"></i>
                            Full chapter support available
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Timeline Estimator -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Dissertation Timeline Estimator</h2>
            <p class="text-xl text-gray-600">Plan your project with our interactive timeline tool</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl mx-auto">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dissertation Type</label>
                        <select id="dissertationType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="masters">Master's Thesis (50-100 pages)</option>
                            <option value="phd">PhD Dissertation (150-300 pages)</option>
                            <option value="proposal">Research Proposal Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Target Completion</label>
                        <select id="timeline" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="6">6 months</option>
                            <option value="9">9 months</option>
                            <option value="12">12 months</option>
                            <option value="18">18 months</option>
                            <option value="24">24 months</option>
                        </select>
                    </div>
                </div>

                <div id="timelineResult" class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8 text-white text-center">
                    <div class="text-lg mb-2">Estimated Timeline</div>
                    <div class="text-4xl font-bold mb-4"><span id="monthsEstimate">12</span> Months</div>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <div class="font-semibold">Chapters 1-3</div>
                            <div class="text-indigo-100"><span id="phase1">4</span> months</div>
                        </div>
                        <div>
                            <div class="font-semibold">Chapters 4-5</div>
                            <div class="text-indigo-100"><span id="phase2">6</span> months</div>
                        </div>
                        <div>
                            <div class="font-semibold">Final Chapter</div>
                            <div class="text-indigo-100"><span id="phase3">2</span> months</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Expert Qualifications -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our PhD Experts</h2>
            <p class="text-xl text-gray-600">Work with doctorate-level researchers in your field</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @php
                $experts = [
                    ['name' => 'Dr. Sarah Mitchell', 'field' => 'Social Sciences', 'phd' => 'PhD, Sociology', 'count' => '45+ Dissertations'],
                    ['name' => 'Dr. James Chen', 'field' => 'STEM', 'phd' => 'PhD, Computer Science', 'count' => '60+ Dissertations'],
                    ['name' => 'Dr. Emily Roberts', 'field' => 'Business', 'phd' => 'PhD, Management', 'count' => '38+ Dissertations'],
                    ['name' => 'Dr. Michael Brown', 'field' => 'Health Sciences', 'phd' => 'PhD, Public Health', 'count' => '52+ Dissertations'],
                ];
            @endphp

            @foreach($experts as $expert)
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($expert['name'], 4, 1) }}
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $expert['name'] }}</h3>
                <p class="text-indigo-600 font-semibold mb-1">{{ $expert['phd'] }}</p>
                <p class="text-sm text-gray-600 mb-3">{{ $expert['field'] }} Specialist</p>
                <div class="flex items-center justify-center gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="h-4 w-4 text-yellow-400 fill-current"></i>
                    @endfor
                </div>
                <div class="text-sm font-semibold text-gray-700">{{ $expert['count'] }}</div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-2xl p-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold text-indigo-600 mb-2">100%</div>
                    <div class="text-sm text-gray-700">PhD Qualified</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-purple-600 mb-2">15+</div>
                    <div class="text-sm text-gray-700">Years Experience</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-pink-600 mb-2">500+</div>
                    <div class="text-sm text-gray-700">Completed Projects</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-rose-600 mb-2">98%</div>
                    <div class="text-sm text-gray-700">Pass Rate</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="py-20 bg-gradient-to-br from-indigo-900 to-purple-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Success Stories</h2>
            <p class="text-xl text-indigo-100">Real students, real achievements</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $stories = [
                    ['name' => 'Jennifer L.', 'degree' => 'PhD in Psychology', 'quote' => 'Their expert guidance helped me complete my dissertation in 18 months. Now I\'m Dr. Jennifer!', 'result' => 'Passed with Distinction'],
                    ['name' => 'Michael R.', 'degree' => 'MBA Thesis', 'quote' => 'The methodology chapter was the hardest part. Their structured approach made it manageable.', 'result' => 'Completed in 8 months'],
                    ['name' => 'Amanda K.', 'degree' => 'Master\'s in Education', 'quote' => 'Professional, timely, and incredibly knowledgeable. Worth every penny for my thesis success.', 'result' => 'Grade: A+'],
                ];
            @endphp

            @foreach($stories as $story)
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="h-5 w-5 text-yellow-400 fill-current"></i>
                    @endfor
                </div>
                <p class="text-lg italic mb-6">"{{ $story['quote'] }}"</p>
                <div class="border-t border-white/20 pt-4">
                    <div class="font-bold text-lg">{{ $story['name'] }}</div>
                    <div class="text-indigo-200 text-sm mb-2">{{ $story['degree'] }}</div>
                    <div class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm inline-block">
                        {{ $story['result'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Consultation Booking -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-12 text-center border-2 border-indigo-200">
            <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="calendar" class="h-10 w-10 text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Free Consultation Available</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Discuss your dissertation project with a PhD expert. Get personalized advice on structure, timeline, and approach.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all transform hover:scale-105">
                    <i data-lucide="video" class="h-5 w-5 mr-2"></i>
                    Book Free Consultation
                </a>
                <a href="#pricing" class="inline-flex items-center justify-center border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-xl font-semibold hover:bg-indigo-50 transition-all">
                    View Pricing
                </a>
            </div>
            <p class="mt-6 text-sm text-gray-600">
                <i data-lucide="clock" class="inline h-4 w-4 mr-1"></i>
                30-minute video call • No obligation • Expert advice
            </p>
        </div>
    </div>
</section>

<!-- Pricing -->
<section id="pricing" class="py-20 bg-gradient-to-br from-gray-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Dissertation Pricing</h2>
            <p class="text-xl text-gray-600">Flexible packages to fit your needs</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @php
                $packages = [
                    ['name' => 'Chapter Support', 'price' => '35', 'unit' => 'per page', 'features' => ['Individual chapters', 'Expert review', 'Unlimited revisions', 'Plagiarism report'], 'icon' => 'file-text'],
                    ['name' => 'Full Dissertation', 'price' => '30', 'unit' => 'per page', 'features' => ['Complete dissertation', 'Priority support', 'Methodology help', 'Defense preparation'], 'popular' => true, 'icon' => 'book'],
                    ['name' => 'Proposal Only', 'price' => '40', 'unit' => 'per page', 'features' => ['Chapters 1-3', 'IRB preparation', 'Fast turnaround', 'Committee feedback'], 'icon' => 'file'],
                ];
            @endphp

            @foreach($packages as $package)
            <div class="relative bg-white rounded-2xl shadow-xl p-8 {{ isset($package['popular']) ? 'ring-4 ring-indigo-500 transform scale-105' : '' }}">
                @if(isset($package['popular']))
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg">
                        Most Popular
                    </span>
                </div>
                @endif

                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="{{ $package['icon'] }}" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package['name'] }}</h3>
                    <div class="text-5xl font-bold text-gray-900 mb-2">${{ $package['price'] }}</div>
                    <p class="text-gray-600">{{ $package['unit'] }}</p>
                </div>

                <ul class="space-y-4 mb-8">
                    @foreach($package['features'] as $feature)
                    <li class="flex items-center">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-4 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all">
                    Get Started
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Start Your Dissertation Journey Today</h2>
        <p class="text-xl text-indigo-100 mb-8">
            Expert PhD guidance from proposal to defense
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center bg-white text-indigo-600 px-10 py-5 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
            Begin Your Dissertation
            <i data-lucide="arrow-right" class="ml-3 h-6 w-6"></i>
        </a>
    </div>
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px) translateX(0px); }
    50% { transform: translateY(-20px) translateX(20px); }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Timeline calculator
    function updateTimeline() {
        const months = parseInt(document.getElementById('timeline').value);
        document.getElementById('monthsEstimate').textContent = months;
        document.getElementById('phase1').textContent = Math.round(months * 0.33);
        document.getElementById('phase2').textContent = Math.round(months * 0.50);
        document.getElementById('phase3').textContent = Math.round(months * 0.17);
    }
    
    document.getElementById('dissertationType').addEventListener('change', updateTimeline);
    document.getElementById('timeline').addEventListener('change', updateTimeline);
});
</script>
@endsection
