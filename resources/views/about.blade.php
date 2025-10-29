@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-on-scroll">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                About <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Scholars Quiver</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Empowering students worldwide with exceptional academic support, expert guidance, and innovative learning solutions since our inception.
            </p>
        </div>
    </div>
</section>

<!-- Mission & Vision Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="animate-on-scroll">
                <h2 class="text-4xl font-bold text-gray-900 mb-8">Our Mission</h2>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    At Scholars Quiver, we believe that every student deserves access to high-quality academic support. Our mission is to bridge the gap between academic challenges and student success by providing personalized, expert-driven educational services.
                </p>
                <p class="text-lg text-gray-600 leading-relaxed mb-8">
                    We are committed to fostering academic excellence, critical thinking, and lifelong learning through innovative approaches and cutting-edge resources.
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center bg-blue-50 px-4 py-2 rounded-full">
                        <i data-lucide="check-circle" class="h-5 w-5 text-blue-600 mr-2"></i>
                        <span class="text-blue-800 font-medium">Student-Centered</span>
                    </div>
                    <div class="flex items-center bg-green-50 px-4 py-2 rounded-full">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-2"></i>
                        <span class="text-green-800 font-medium">Quality Assured</span>
                    </div>
                    <div class="flex items-center bg-purple-50 px-4 py-2 rounded-full">
                        <i data-lucide="check-circle" class="h-5 w-5 text-purple-600 mr-2"></i>
                        <span class="text-purple-800 font-medium">Innovation Driven</span>
                    </div>
                </div>
            </div>
            <div class="animate-on-scroll">
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6">Our Vision</h3>
                    <p class="text-blue-100 leading-relaxed mb-6">
                        To become the world's leading platform for academic excellence, where students from all backgrounds can access premium educational support and achieve their full potential.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">50K+</div>
                            <div class="text-blue-200 text-sm">Students Helped</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">500+</div>
                            <div class="text-blue-200 text-sm">Expert Tutors</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">98%</div>
                            <div class="text-blue-200 text-sm">Success Rate</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white mb-2">24/7</div>
                            <div class="text-blue-200 text-sm">Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Story</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Founded by a team of passionate educators and technology enthusiasts, Scholars Quiver was born from a simple yet powerful idea.
            </p>
        </div>

        <div class="relative">
            <!-- Timeline -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-blue-500 to-purple-600 hidden lg:block"></div>
            
            <div class="space-y-16">
                <!-- Timeline Item 1 -->
                <div class="flex flex-col lg:flex-row items-center animate-on-scroll">
                    <div class="lg:w-1/2 lg:pr-12 mb-8 lg:mb-0">
                        <div class="bg-white p-8 rounded-2xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-4">2020</div>
                                <h3 class="text-2xl font-bold text-gray-900">The Beginning</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Started as a small tutoring service by university graduates who recognized the need for personalized academic support in the digital age.
                            </p>
                        </div>
                    </div>
                    <div class="hidden lg:block w-6 h-6 bg-blue-500 rounded-full border-4 border-white shadow-lg"></div>
                    <div class="lg:w-1/2 lg:pl-12"></div>
                </div>

                <!-- Timeline Item 2 -->
                <div class="flex flex-col lg:flex-row items-center animate-on-scroll">
                    <div class="lg:w-1/2 lg:pr-12"></div>
                    <div class="hidden lg:block w-6 h-6 bg-purple-500 rounded-full border-4 border-white shadow-lg"></div>
                    <div class="lg:w-1/2 lg:pl-12 mb-8 lg:mb-0">
                        <div class="bg-white p-8 rounded-2xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-4">2021</div>
                                <h3 class="text-2xl font-bold text-gray-900">Rapid Growth</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Expanded our team to include subject matter experts from top universities and developed our proprietary learning management system.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timeline Item 3 -->
                <div class="flex flex-col lg:flex-row items-center animate-on-scroll">
                    <div class="lg:w-1/2 lg:pr-12 mb-8 lg:mb-0">
                        <div class="bg-white p-8 rounded-2xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-4">2022</div>
                                <h3 class="text-2xl font-bold text-gray-900">Innovation</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Launched our AI-powered matching system and introduced premium study resources, revolutionizing how students connect with academic support.
                            </p>
                        </div>
                    </div>
                    <div class="hidden lg:block w-6 h-6 bg-green-500 rounded-full border-4 border-white shadow-lg"></div>
                    <div class="lg:w-1/2 lg:pl-12"></div>
                </div>

                <!-- Timeline Item 4 -->
                <div class="flex flex-col lg:flex-row items-center animate-on-scroll">
                    <div class="lg:w-1/2 lg:pr-12"></div>
                    <div class="hidden lg:block w-6 h-6 bg-orange-500 rounded-full border-4 border-white shadow-lg"></div>
                    <div class="lg:w-1/2 lg:pl-12">
                        <div class="bg-white p-8 rounded-2xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold mr-4">2024</div>
                                <h3 class="text-2xl font-bold text-gray-900">Global Reach</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Today, we serve students worldwide with a comprehensive platform that combines expert tutoring, premium resources, and cutting-edge technology.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Meet Our Leadership Team</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Passionate educators and innovators dedicated to transforming academic support.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @php
                $team = [
                    [
                        'name' => 'Dr. Sarah Johnson',
                        'role' => 'Chief Executive Officer',
                        'image' => 'https://randomuser.me/api/portraits/women/32.jpg',
                        'bio' => 'Former Harvard professor with 15+ years in educational technology.',
                        'linkedin' => '#'
                    ],
                    [
                        'name' => 'Michael Chen',
                        'role' => 'Chief Technology Officer',
                        'image' => 'https://randomuser.me/api/portraits/men/45.jpg',
                        'bio' => 'MIT graduate specializing in AI and machine learning applications.',
                        'linkedin' => '#'
                    ],
                    [
                        'name' => 'Dr. Emily Rodriguez',
                        'role' => 'Head of Academic Affairs',
                        'image' => 'https://randomuser.me/api/portraits/women/28.jpg',
                        'bio' => 'Oxford PhD with expertise in curriculum development and assessment.',
                        'linkedin' => '#'
                    ]
                ];
            @endphp

            @foreach($team as $member)
                <div class="group text-center animate-on-scroll">
                    <div class="relative mb-6">
                        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                        <div class="absolute inset-0 w-32 h-32 rounded-full mx-auto bg-gradient-to-br from-blue-500 to-purple-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $member['name'] }}</h3>
                    <p class="text-blue-600 font-semibold mb-4">{{ $member['role'] }}</p>
                    <p class="text-gray-600 leading-relaxed mb-4">{{ $member['bio'] }}</p>
                    <a href="{{ $member['linkedin'] }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200">
                        <i data-lucide="linkedin" class="h-5 w-5 mr-2"></i>
                        Connect
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Core Values</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                The principles that guide everything we do at Scholars Quiver.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $values = [
                    [
                        'icon' => 'heart',
                        'title' => 'Student First',
                        'description' => 'Every decision we make prioritizes student success and well-being.',
                        'color' => 'from-red-500 to-pink-600'
                    ],
                    [
                        'icon' => 'award',
                        'title' => 'Excellence',
                        'description' => 'We maintain the highest standards in everything we deliver.',
                        'color' => 'from-yellow-500 to-orange-600'
                    ],
                    [
                        'icon' => 'shield',
                        'title' => 'Integrity',
                        'description' => 'Honest, ethical practices in all our interactions and services.',
                        'color' => 'from-green-500 to-emerald-600'
                    ],
                    [
                        'icon' => 'zap',
                        'title' => 'Innovation',
                        'description' => 'Continuously evolving to meet changing educational needs.',
                        'color' => 'from-blue-500 to-purple-600'
                    ]
                ];
            @endphp

            @foreach($values as $value)
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                    <div class="text-center">
                        <div class="h-16 w-16 bg-gradient-to-br {{ $value['color'] }} rounded-2xl flex items-center justify-center text-white mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="{{ $value['icon'] }}" class="h-8 w-8"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $value['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $value['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Join Our Academic Community</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto leading-relaxed">
            Become part of a thriving community of learners, educators, and innovators committed to academic excellence.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105">
                Get Started Today
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-colors duration-300 transform hover:scale-105">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection
