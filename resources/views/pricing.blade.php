@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-on-scroll">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Simple <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Pricing</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Transparent, competitive pricing for all our academic services. Quality education support that fits your budget.
            </p>
        </div>
    </div>
</section>

<!-- Pricing Tables -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Writing Services Pricing -->
        <div class="mb-20 animate-on-scroll">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Writing Services</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Professional academic writing with guaranteed quality and on-time delivery.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $writingPlans = [
                        [
                            'level' => 'High School',
                            'price' => '$15',
                            'description' => 'Perfect for high school assignments',
                            'features' => [
                                'Basic research and writing',
                                'Standard formatting',
                                'Free revisions (3)',
                                '14-day delivery',
                                'Plagiarism report'
                            ],
                            'color' => 'from-blue-500 to-blue-600'
                        ],
                        [
                            'level' => 'Undergraduate',
                            'price' => '$20',
                            'description' => 'Ideal for college-level work',
                            'features' => [
                                'Advanced research',
                                'Multiple citation styles',
                                'Free revisions (5)',
                                '10-day delivery',
                                'Plagiarism report',
                                'Quality assurance'
                            ],
                            'color' => 'from-green-500 to-green-600',
                            'popular' => true
                        ],
                        [
                            'level' => 'Graduate',
                            'price' => '$25',
                            'description' => 'For master\'s level projects',
                            'features' => [
                                'Expert-level research',
                                'Advanced methodology',
                                'Unlimited revisions',
                                '7-day delivery',
                                'Plagiarism report',
                                'Quality assurance',
                                'Direct expert contact'
                            ],
                            'color' => 'from-purple-500 to-purple-600'
                        ],
                        [
                            'level' => 'PhD',
                            'price' => '$30',
                            'description' => 'Doctoral-level excellence',
                            'features' => [
                                'PhD-level expertise',
                                'Original research',
                                'Unlimited revisions',
                                '5-day delivery',
                                'Plagiarism report',
                                'Quality assurance',
                                'Direct expert contact',
                                'Progress updates'
                            ],
                            'color' => 'from-orange-500 to-orange-600'
                        ]
                    ];
                @endphp

                @foreach($writingPlans as $plan)
                    <div class="relative bg-white rounded-2xl shadow-lg border {{ isset($plan['popular']) ? 'border-green-500 transform scale-105' : 'border-gray-200' }} hover:shadow-2xl transition-all duration-300">
                        @if(isset($plan['popular']))
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                            </div>
                        @endif
                        
                        <div class="p-8">
                            <div class="text-center mb-8">
                                <div class="h-16 w-16 bg-gradient-to-br {{ $plan['color'] }} rounded-2xl flex items-center justify-center text-white mx-auto mb-4">
                                    <i data-lucide="pen-tool" class="h-8 w-8"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan['level'] }}</h3>
                                <p class="text-gray-600 text-sm">{{ $plan['description'] }}</p>
                            </div>
                            
                            <div class="text-center mb-8">
                                <span class="text-4xl font-bold text-gray-900">{{ $plan['price'] }}</span>
                                <span class="text-gray-600">/page</span>
                            </div>
                            
                            <ul class="space-y-3 mb-8">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <i data-lucide="check" class="h-4 w-4 text-green-500 mr-3"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <button class="w-full bg-gradient-to-r {{ $plan['color'] }} text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition-opacity duration-200">
                                Order Now
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tutoring Services Pricing -->
        <div class="mb-20 animate-on-scroll">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Tutoring Services</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Flexible tutoring options with qualified instructors across all subjects.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $tutoringPlans = [
                        [
                            'type' => 'One-on-One',
                            'price' => '$25-50',
                            'description' => 'Personalized attention',
                            'features' => [
                                'Individual sessions',
                                'Customized learning plan',
                                'Flexible scheduling',
                                'Progress tracking',
                                'Direct messaging',
                                'Session recordings'
                            ],
                            'color' => 'from-blue-500 to-blue-600'
                        ],
                        [
                            'type' => 'Group Sessions',
                            'price' => '$15-25',
                            'description' => 'Collaborative learning',
                            'features' => [
                                'Small groups (3-6 students)',
                                'Interactive sessions',
                                'Peer learning',
                                'Shared resources',
                                'Group discussions',
                                'Cost-effective'
                            ],
                            'color' => 'from-green-500 to-green-600',
                            'popular' => true
                        ],
                        [
                            'type' => 'Test Preparation',
                            'price' => '$30-60',
                            'description' => 'Exam-focused coaching',
                            'features' => [
                                'Specialized test prep',
                                'Practice tests',
                                'Strategy sessions',
                                'Performance analysis',
                                'Study materials included',
                                'Score improvement guarantee'
                            ],
                            'color' => 'from-purple-500 to-purple-600'
                        ]
                    ];
                @endphp

                @foreach($tutoringPlans as $plan)
                    <div class="relative bg-white rounded-2xl shadow-lg border {{ isset($plan['popular']) ? 'border-green-500 transform scale-105' : 'border-gray-200' }} hover:shadow-2xl transition-all duration-300">
                        @if(isset($plan['popular']))
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                            </div>
                        @endif
                        
                        <div class="p-8">
                            <div class="text-center mb-8">
                                <div class="h-16 w-16 bg-gradient-to-br {{ $plan['color'] }} rounded-2xl flex items-center justify-center text-white mx-auto mb-4">
                                    <i data-lucide="users" class="h-8 w-8"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan['type'] }}</h3>
                                <p class="text-gray-600 text-sm">{{ $plan['description'] }}</p>
                            </div>
                            
                            <div class="text-center mb-8">
                                <span class="text-3xl font-bold text-gray-900">{{ $plan['price'] }}</span>
                                <span class="text-gray-600">/hour</span>
                            </div>
                            
                            <ul class="space-y-3 mb-8">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <i data-lucide="check" class="h-4 w-4 text-green-500 mr-3"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <button class="w-full bg-gradient-to-r {{ $plan['color'] }} text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition-opacity duration-200">
                                Book Session
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Study Resources Pricing -->
        <div class="animate-on-scroll">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Study Resources</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Premium study materials and resources created by academic experts.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $resourceTypes = [
                        [
                            'type' => 'Study Notes',
                            'price' => '$5-15',
                            'icon' => 'file-text',
                            'description' => 'Comprehensive notes on key topics',
                            'color' => 'from-blue-500 to-blue-600'
                        ],
                        [
                            'type' => 'Sample Papers',
                            'price' => '$10-25',
                            'icon' => 'file-check',
                            'description' => 'High-quality example papers',
                            'color' => 'from-green-500 to-green-600'
                        ],
                        [
                            'type' => 'Study Guides',
                            'price' => '$15-30',
                            'icon' => 'book-open',
                            'description' => 'Structured learning materials',
                            'color' => 'from-purple-500 to-purple-600'
                        ],
                        [
                            'type' => 'Premium Bundles',
                            'price' => '$50+',
                            'icon' => 'package',
                            'description' => 'Complete subject packages',
                            'color' => 'from-orange-500 to-orange-600'
                        ]
                    ];
                @endphp

                @foreach($resourceTypes as $resource)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-2xl transition-all duration-300 p-6 text-center">
                        <div class="h-16 w-16 bg-gradient-to-br {{ $resource['color'] }} rounded-2xl flex items-center justify-center text-white mx-auto mb-4">
                            <i data-lucide="{{ $resource['icon'] }}" class="h-8 w-8"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $resource['type'] }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $resource['description'] }}</p>
                        <div class="text-2xl font-bold text-gray-900 mb-4">{{ $resource['price'] }}</div>
                        <button class="w-full bg-gradient-to-r {{ $resource['color'] }} text-white py-2 px-4 rounded-lg font-semibold hover:opacity-90 transition-opacity duration-200 text-sm">
                            Browse Resources
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Additional Services -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Additional Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Extra services to enhance your academic experience and ensure your success.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $additionalServices = [
                    [
                        'service' => 'Rush Delivery',
                        'price' => '+50%',
                        'description' => '24-48 hour delivery',
                        'icon' => 'zap',
                        'color' => 'from-red-500 to-red-600'
                    ],
                    [
                        'service' => 'Plagiarism Report',
                        'price' => 'Free',
                        'description' => 'Detailed originality report',
                        'icon' => 'shield-check',
                        'color' => 'from-green-500 to-green-600'
                    ],
                    [
                        'service' => 'Progressive Delivery',
                        'price' => 'Free',
                        'description' => 'Receive work in parts',
                        'icon' => 'layers',
                        'color' => 'from-blue-500 to-blue-600'
                    ],
                    [
                        'service' => 'VIP Support',
                        'price' => '+25%',
                        'description' => 'Priority customer service',
                        'icon' => 'crown',
                        'color' => 'from-yellow-500 to-yellow-600'
                    ],
                    [
                        'service' => 'Extended Revisions',
                        'price' => '+$10',
                        'description' => 'Additional revision rounds',
                        'icon' => 'refresh-cw',
                        'color' => 'from-purple-500 to-purple-600'
                    ],
                    [
                        'service' => 'Abstract/Summary',
                        'price' => '+$15',
                        'description' => 'Professional abstract writing',
                        'icon' => 'file-plus',
                        'color' => 'from-indigo-500 to-indigo-600'
                    ]
                ];
            @endphp

            @foreach($additionalServices as $service)
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 animate-on-scroll">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-br {{ $service['color'] }} rounded-xl flex items-center justify-center text-white mr-4">
                            <i data-lucide="{{ $service['icon'] }}" class="h-6 w-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $service['service'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $service['description'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-gray-900">{{ $service['price'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Pricing FAQs</h2>
            <p class="text-lg text-gray-600">Common questions about our pricing and payment options.</p>
        </div>
        
        <div class="space-y-6" x-data="{ openFaq: null }">
            @php
                $pricingFaqs = [
                    [
                        'question' => 'How is the final price calculated?',
                        'answer' => 'The price depends on academic level, deadline, number of pages, and any additional services. Our calculator provides an instant quote based on your requirements.'
                    ],
                    [
                        'question' => 'Do you offer discounts for bulk orders?',
                        'answer' => 'Yes! We offer progressive discounts for larger orders and returning customers. Contact us for custom pricing on bulk orders over 20 pages.'
                    ],
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept all major credit cards, PayPal, bank transfers, and mobile money (M-Pesa). All payments are processed securely through encrypted channels.'
                    ],
                    [
                        'question' => 'Is there a money-back guarantee?',
                        'answer' => 'Yes, we offer a 100% money-back guarantee if you\'re not satisfied with the quality of work, subject to our terms and conditions.'
                    ],
                    [
                        'question' => 'Are revisions included in the price?',
                        'answer' => 'Yes, free revisions are included based on your academic level. High school gets 3 free revisions, undergraduate gets 5, and graduate/PhD levels get unlimited revisions within 14 days.'
                    ]
                ];
            @endphp

            @foreach($pricingFaqs as $index => $faq)
                <div class="bg-gray-50 rounded-xl overflow-hidden animate-on-scroll">
                    <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}" 
                            class="w-full px-6 py-4 text-left focus:outline-none hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $faq['question'] }}</h3>
                            <i data-lucide="chevron-down" class="h-5 w-5 text-gray-500 transform transition-transform duration-200"
                               :class="{ 'rotate-180': openFaq === {{ $index }} }"></i>
                        </div>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-transition class="px-6 pb-4">
                        <p class="text-gray-600 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-6">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto leading-relaxed">
            Choose your service and get an instant quote. Quality academic support at transparent, competitive prices.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105">
                Get Started Now
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-colors duration-300 transform hover:scale-105">
                Get Custom Quote
            </a>
        </div>
    </div>
</section>
@endsection
