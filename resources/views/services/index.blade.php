@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-on-scroll">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Services</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Comprehensive academic support tailored to your educational needs. From writing assistance to tutoring and premium resources.
            </p>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Writing Services -->
            <div class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                <div class="text-center mb-8">
                    <div class="h-20 w-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="pen-tool" class="h-10 w-10"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Writing Services</h2>
                    <p class="text-gray-600 leading-relaxed">Professional academic writing assistance from expert writers with advanced degrees.</p>
                </div>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-blue-600 mr-3"></i>
                        <span>Essays & Term Papers</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-blue-600 mr-3"></i>
                        <span>Research Papers</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-blue-600 mr-3"></i>
                        <span>Dissertations & Theses</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-blue-600 mr-3"></i>
                        <span>CV & Personal Statements</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('services.writing.essays') }}" class="block w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center font-semibold">
                        View Writing Services
                    </a>
                    <p class="text-sm text-gray-500 text-center">Starting from $15/page</p>
                </div>
            </div>

            <!-- Tutoring Services -->
            <div class="group bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                <div class="text-center mb-8">
                    <div class="h-20 w-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="users" class="h-10 w-10"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Tutoring Services</h2>
                    <p class="text-gray-600 leading-relaxed">One-on-one and group tutoring sessions with qualified instructors across all subjects.</p>
                </div>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <span>One-on-One Tutoring</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <span>Group Sessions</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <span>Test Preparation</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <span>Subject-Specific Help</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('services.tutoring.one-on-one') }}" class="block w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200 text-center font-semibold">
                        View Tutoring Services
                    </a>
                    <p class="text-sm text-gray-500 text-center">Starting from $25/hour</p>
                </div>
            </div>

            <!-- Study Resources -->
            <div class="group bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll">
                <div class="text-center mb-8">
                    <div class="h-20 w-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="book-open" class="h-10 w-10"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Study Resources</h2>
                    <p class="text-gray-600 leading-relaxed">Premium study materials, guides, and resources created by academic experts.</p>
                </div>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-purple-600 mr-3"></i>
                        <span>Study Notes & Guides</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-purple-600 mr-3"></i>
                        <span>Sample Papers</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-purple-600 mr-3"></i>
                        <span>Citation Help</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <i data-lucide="check-circle" class="h-5 w-5 text-purple-600 mr-3"></i>
                        <span>Research Tools</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('services.resources.notes') }}" class="block w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors duration-200 text-center font-semibold">
                        View Study Resources
                    </a>
                    <p class="text-sm text-gray-500 text-center">Starting from $5/resource</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Services -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Comprehensive Academic Support</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Explore our full range of services designed to support every aspect of your academic journey.
            </p>
        </div>

        <!-- Writing Services Detail -->
        <div class="mb-20 animate-on-scroll">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i data-lucide="pen-tool" class="h-8 w-8 mr-4"></i>
                        Writing Services
                    </h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">What We Offer</h4>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-blue-600 mr-3 mt-0.5"></i>
                                    <span><strong>Essays & Term Papers:</strong> Custom written academic papers tailored to your requirements</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-blue-600 mr-3 mt-0.5"></i>
                                    <span><strong>Research Papers:</strong> In-depth research with proper citations and methodology</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-blue-600 mr-3 mt-0.5"></i>
                                    <span><strong>Dissertations & Theses:</strong> Comprehensive support for graduate-level projects</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-blue-600 mr-3 mt-0.5"></i>
                                    <span><strong>CV & Personal Statements:</strong> Professional documents for applications</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Why Choose Our Writers</h4>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>PhD and Master's degree holders</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Subject matter experts</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Native English speakers</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Plagiarism-free guarantee</span>
                                </li>
                            </ul>
                            <div class="mt-6">
                                <a href="{{ route('services.writing.essays') }}" class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-semibold">
                                    Explore Writing Services
                                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tutoring Services Detail -->
        <div class="mb-20 animate-on-scroll">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i data-lucide="users" class="h-8 w-8 mr-4"></i>
                        Tutoring Services
                    </h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Tutoring Options</h4>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-green-600 mr-3 mt-0.5"></i>
                                    <span><strong>One-on-One Sessions:</strong> Personalized attention for maximum learning</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-green-600 mr-3 mt-0.5"></i>
                                    <span><strong>Group Sessions:</strong> Collaborative learning with peers</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-green-600 mr-3 mt-0.5"></i>
                                    <span><strong>Test Preparation:</strong> Focused prep for exams and standardized tests</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-green-600 mr-3 mt-0.5"></i>
                                    <span><strong>Subject-Specific Help:</strong> Expert guidance in your area of study</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Our Tutors</h4>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Certified educators</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Advanced degrees in their fields</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Proven track record</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Flexible scheduling</span>
                                </li>
                            </ul>
                            <div class="mt-6">
                                <a href="{{ route('services.tutoring.one-on-one') }}" class="inline-flex items-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 font-semibold">
                                    Find a Tutor
                                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Study Resources Detail -->
        <div class="animate-on-scroll">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i data-lucide="book-open" class="h-8 w-8 mr-4"></i>
                        Study Resources
                    </h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Available Resources</h4>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-purple-600 mr-3 mt-0.5"></i>
                                    <span><strong>Study Notes:</strong> Comprehensive notes on key topics</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-purple-600 mr-3 mt-0.5"></i>
                                    <span><strong>Sample Papers:</strong> Examples of high-quality academic work</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-purple-600 mr-3 mt-0.5"></i>
                                    <span><strong>Study Guides:</strong> Structured learning materials</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="arrow-right" class="h-5 w-5 text-purple-600 mr-3 mt-0.5"></i>
                                    <span><strong>Citation Tools:</strong> Proper formatting and referencing help</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Resource Quality</h4>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Expert-created content</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Regularly updated materials</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Multiple formats available</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                                    <span>Instant download access</span>
                                </li>
                            </ul>
                            <div class="mt-6">
                                <a href="{{ route('knowledge-resources.index') }}" class="inline-flex items-center bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors duration-200 font-semibold">
                                    Browse Resources
                                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Overview -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Transparent Pricing</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Quality academic support at competitive prices. No hidden fees, just exceptional value.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 text-center animate-on-scroll">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Writing Services</h3>
                <div class="text-3xl font-bold text-blue-600 mb-2">$15+</div>
                <p class="text-gray-600 mb-6">per page</p>
                <ul class="text-sm text-gray-600 space-y-2 mb-6">
                    <li>• High school: $15/page</li>
                    <li>• Undergraduate: $20/page</li>
                    <li>• Graduate: $25/page</li>
                    <li>• PhD: $30/page</li>
                </ul>
                <a href="{{ route('pricing') }}" class="text-blue-600 font-semibold hover:text-blue-700">View Details →</a>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 text-center animate-on-scroll">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Tutoring Services</h3>
                <div class="text-3xl font-bold text-green-600 mb-2">$25+</div>
                <p class="text-gray-600 mb-6">per hour</p>
                <ul class="text-sm text-gray-600 space-y-2 mb-6">
                    <li>• One-on-one: $25-50/hour</li>
                    <li>• Group sessions: $15-25/hour</li>
                    <li>• Test prep: $30-60/hour</li>
                    <li>• Specialized subjects: $40+/hour</li>
                </ul>
                <a href="{{ route('pricing') }}" class="text-green-600 font-semibold hover:text-green-700">View Details →</a>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 text-center animate-on-scroll">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Study Resources</h3>
                <div class="text-3xl font-bold text-purple-600 mb-2">$5+</div>
                <p class="text-gray-600 mb-6">per resource</p>
                <ul class="text-sm text-gray-600 space-y-2 mb-6">
                    <li>• Study notes: $5-15</li>
                    <li>• Sample papers: $10-25</li>
                    <li>• Study guides: $15-30</li>
                    <li>• Premium bundles: $50+</li>
                </ul>
                <a href="{{ route('pricing') }}" class="text-purple-600 font-semibold hover:text-purple-700">View Details →</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-6">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto leading-relaxed">
            Choose the service that best fits your needs and take the first step toward academic success.
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
