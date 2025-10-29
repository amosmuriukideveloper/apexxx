@extends('layouts.landing')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-purple-900 overflow-hidden min-h-screen flex items-center">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
            <div class="absolute top-3/4 right-1/4 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse delay-1000"></div>
            <div class="absolute bottom-1/4 left-1/2 w-64 h-64 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse delay-2000"></div>
        </div>
        
        <div class="absolute inset-0">
            <img src="https://mg.co.za/wp-content/uploads/2019/03/d98b00fd-class-and-social-capital-affect-university-students.jpeg" 
                 alt="Students studying" 
                 class="w-full h-full object-cover opacity-10" />
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center animate-on-scroll">
                <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-8 leading-tight">
                    <span class="block">Scholars Quiver</span>
                </h1>
                <p class="mt-8 max-w-3xl mx-auto text-xl md:text-2xl text-blue-100 leading-relaxed">
                    Professional academic assistance, expert consultations, and
                    premium study resources all in one place.
                </p>
                <div class="mt-12 flex flex-col sm:flex-row gap-6 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-blue-500/25">
                            <span class="relative z-10">Get Started Today</span>
                            <i data-lucide="arrow-right" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-blue-100 border-2 border-blue-200 rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                            Sign In
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-2xl">
                            Go to Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i data-lucide="chevron-down" class="h-8 w-8 text-white opacity-60"></i>
        </div>
    </div>

    <!-- Services Section -->
    <div class="py-20 bg-gradient-to-br from-gray-50 to-blue-50" id="services">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-on-scroll">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase mb-4">Our Services</h2>
                <h3 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    Comprehensive Academic Solutions
                </h3>
                <p class="max-w-3xl mx-auto text-xl text-gray-600 leading-relaxed">
                    Explore our range of services designed to support your academic journey
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Service 1 -->
                <div class="group relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="h-16 w-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="pen-tool" class="h-8 w-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Writing Services</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Professional academic writing assistance for essays, research papers, dissertations, and more.</p>
                        <a href="{{ route('services.writing.essays') }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-200">
                            Learn More
                            <i data-lucide="arrow-right" class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="group relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="h-16 w-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="users" class="h-8 w-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Tutoring</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">One-on-one and group tutoring sessions with expert instructors across all subjects.</p>
                        <a href="{{ route('services.tutoring.one-on-one') }}" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition-colors duration-200">
                            Learn More
                            <i data-lucide="arrow-right" class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="group relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="h-16 w-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="book-open" class="h-8 w-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Study Resources</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Premium study materials, guides, and resources created by academic experts.</p>
                        <a href="{{ route('services.resources.notes') }}" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700 transition-colors duration-200">
                            Learn More
                            <i data-lucide="arrow-right" class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('services.index') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    View All Services
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-on-scroll">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase mb-4">How It Works</h2>
                <h3 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    Simple Process, Exceptional Results
                </h3>
                <p class="max-w-3xl mx-auto text-xl text-gray-600 leading-relaxed">
                    Our streamlined process ensures you get the academic help you need quickly and efficiently.
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-12 md:grid-cols-4">
                @php
                    $steps = [
                        [
                            'number' => '1',
                            'title' => 'Submit Requirements',
                            'description' => 'Provide details about your project, assignment, or consultation needs.',
                            'icon' => 'file-plus'
                        ],
                        [
                            'number' => '2',
                            'title' => 'Get Matched',
                            'description' => 'We connect you with the most suitable expert for your specific requirements.',
                            'icon' => 'users'
                        ],
                        [
                            'number' => '3',
                            'title' => 'Collaborate',
                            'description' => 'Work directly with your expert, track progress, and provide feedback.',
                            'icon' => 'message-circle'
                        ],
                        [
                            'number' => '4',
                            'title' => 'Receive Solution',
                            'description' => 'Get your completed work on time and ready for submission.',
                            'icon' => 'check-circle'
                        ]
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="text-center animate-on-scroll group">
                        <div class="relative mb-8">
                            <div class="mx-auto h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <span class="text-2xl font-bold">{{ $step['number'] }}</span>
                            </div>
                            @if($index < count($steps) - 1)
                                <div class="hidden md:block absolute top-12 left-full w-full h-0.5 bg-gradient-to-r from-blue-200 to-purple-200"></div>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $step['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-on-scroll">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase mb-4">Why Choose Us</h2>
                <h3 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    The Scholars Quiver Advantage
                </h3>
                <p class="max-w-3xl mx-auto text-xl text-gray-600 leading-relaxed">
                    We stand out from other academic services with our commitment to quality, security, and student success.
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $features = [
                        [
                            'icon' => 'check-circle',
                            'title' => 'Quality Guaranteed',
                            'description' => 'All our experts are thoroughly vetted and held to the highest academic standards.',
                            'color' => 'from-green-500 to-emerald-600'
                        ],
                        [
                            'icon' => 'clock',
                            'title' => 'On-Time Delivery',
                            'description' => 'We understand the importance of deadlines in academia and always deliver on time.',
                            'color' => 'from-blue-500 to-cyan-600'
                        ],
                        [
                            'icon' => 'shield-check',
                            'title' => '100% Privacy',
                            'description' => 'Your personal information and project details are kept completely confidential.',
                            'color' => 'from-purple-500 to-pink-600'
                        ],
                        [
                            'icon' => 'star',
                            'title' => 'Expert Scholars',
                            'description' => 'Work with professionals holding advanced degrees from prestigious universities.',
                            'color' => 'from-yellow-500 to-orange-600'
                        ],
                        [
                            'icon' => 'briefcase',
                            'title' => 'Custom Solutions',
                            'description' => 'Every project is tailored to your specific requirements and academic goals.',
                            'color' => 'from-indigo-500 to-purple-600'
                        ],
                        [
                            'icon' => 'headphones',
                            'title' => '24/7 Support',
                            'description' => 'Our customer support team is available around the clock to assist you.',
                            'color' => 'from-red-500 to-pink-600'
                        ]
                    ];
                @endphp

                @foreach($features as $feature)
                    <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br {{ $feature['color'] }} text-white group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="{{ $feature['icon'] }}" class="h-8 w-8"></i>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 py-20">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-8">
                Ready to excel in your studies?
            </h2>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-12 leading-relaxed">
                Join thousands of students who have achieved academic success with our expert support and comprehensive resources.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-blue-600 bg-white rounded-xl hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Get Started Today
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white border-2 border-white rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                    Sign In
                </a>
            </div>
        </div>
    </div>
@endsection
