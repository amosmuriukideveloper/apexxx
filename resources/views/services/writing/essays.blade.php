@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-20 overflow-hidden">
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fade-in">
            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mb-6">
                Professional Essay Writing Service
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                Expert <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Essay Writing</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                Get custom essays written by qualified academic writers with advanced degrees. 
                100% plagiarism-free, on-time delivery, and unlimited revisions.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                    Order Essay Now
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#pricing" class="inline-flex items-center justify-center border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-blue-50 transition-all">
                    View Pricing
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Essay Types -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Types of Essays We Write</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                We cover all essay types across all academic levels
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $essayTypes = [
                    ['name' => 'Argumentative Essays', 'icon' => 'message-square', 'description' => 'Persuasive writing with strong arguments'],
                    ['name' => 'Descriptive Essays', 'icon' => 'eye', 'description' => 'Vivid descriptions and sensory details'],
                    ['name' => 'Narrative Essays', 'icon' => 'book', 'description' => 'Story-telling with personal experiences'],
                    ['name' => 'Expository Essays', 'icon' => 'info', 'description' => 'Informative writing with facts'],
                    ['name' => 'Analytical Essays', 'icon' => 'search', 'description' => 'Critical analysis and interpretation'],
                    ['name' => 'Compare & Contrast', 'icon' => 'git-compare', 'description' => 'Similarities and differences analysis'],
                ];
            @endphp

            @foreach($essayTypes as $type)
            <div class="group bg-gradient-to-br from-white to-blue-50 border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $type['icon'] }}" class="h-7 w-7"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $type['name'] }}</h3>
                <p class="text-gray-600">{{ $type['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Interactive Pricing Calculator -->
<section id="pricing" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Instant Price Calculator</h2>
            <p class="text-xl text-gray-600">Get your custom quote in seconds</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Academic Level</label>
                    <select id="academicLevel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="15">High School ($15/page)</option>
                        <option value="20">Undergraduate ($20/page)</option>
                        <option value="25">Graduate ($25/page)</option>
                        <option value="30">PhD ($30/page)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Pages</label>
                    <input type="number" id="numPages" value="5" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deadline</label>
                    <select id="deadline" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="1.0">10+ days</option>
                        <option value="1.2">7 days (+20%)</option>
                        <option value="1.4">5 days (+40%)</option>
                        <option value="1.7">3 days (+70%)</option>
                        <option value="2.0">2 days (+100%)</option>
                        <option value="2.5">24 hours (+150%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Urgency</label>
                    <div class="w-full px-4 py-3 bg-blue-50 rounded-lg text-blue-800 font-semibold">
                        <span id="urgencyText">Standard</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-8 text-white text-center">
                <div class="text-lg mb-2">Estimated Total</div>
                <div class="text-5xl font-bold mb-4">$<span id="totalPrice">75.00</span></div>
                <div class="text-blue-100 text-sm mb-6">Price includes unlimited revisions & plagiarism report</div>
                <a href="{{ route('register') }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105">
                    Proceed to Order
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Order Process Timeline -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
            <p class="text-xl text-gray-600">Simple 4-step process to get your essay</p>
        </div>

        <div class="relative">
            <!-- Timeline Line -->
            <div class="hidden md:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200 transform -translate-y-1/2"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                @php
                    $steps = [
                        ['num' => '1', 'title' => 'Place Order', 'desc' => 'Fill out the order form with your requirements', 'icon' => 'file-text', 'color' => 'blue'],
                        ['num' => '2', 'title' => 'Writer Assigned', 'desc' => 'We match you with the best expert writer', 'icon' => 'user-check', 'color' => 'purple'],
                        ['num' => '3', 'title' => 'Writing Process', 'desc' => 'Your writer works on your essay', 'icon' => 'pen-tool', 'color' => 'pink'],
                        ['num' => '4', 'title' => 'Download Essay', 'desc' => 'Receive your completed essay on time', 'icon' => 'download', 'color' => 'green'],
                    ];
                @endphp

                @foreach($steps as $step)
                <div class="relative bg-white">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative z-10 w-20 h-20 bg-gradient-to-br from-{{ $step['color'] }}-500 to-{{ $step['color'] }}-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4 shadow-lg animate-bounce-slow">
                            <i data-lucide="{{ $step['icon'] }}" class="h-10 w-10"></i>
                        </div>
                        <div class="absolute top-10 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-4xl font-bold text-gray-100 z-0">
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

<!-- Writer Profiles -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Meet Our Expert Writers</h2>
            <p class="text-xl text-gray-600">PhD and Master's degree holders from top universities</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for($i = 1; $i <= 3; $i++)
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold">
                    {{ chr(64 + $i) }}
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Dr. Writer {{ $i }}</h3>
                <p class="text-blue-600 font-semibold mb-4">PhD in English Literature</p>
                <div class="flex items-center justify-center gap-1 mb-4">
                    @for($j = 1; $j <= 5; $j++)
                        <i data-lucide="star" class="h-5 w-5 text-yellow-400 fill-current"></i>
                    @endfor
                    <span class="ml-2 text-gray-600">(5.0)</span>
                </div>
                <div class="text-sm text-gray-600 space-y-2">
                    <div class="flex items-center justify-center">
                        <i data-lucide="check-circle" class="h-4 w-4 text-green-600 mr-2"></i>
                        500+ Essays Completed
                    </div>
                    <div class="flex items-center justify-center">
                        <i data-lucide="award" class="h-4 w-4 text-blue-600 mr-2"></i>
                        10+ Years Experience
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Students Say</h2>
            <p class="text-xl text-gray-600">Trusted by thousands of students worldwide</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $testimonials = [
                    ['name' => 'Sarah M.', 'rating' => 5, 'text' => 'Amazing quality! Got an A+ on my argumentative essay. The writer followed all instructions perfectly.'],
                    ['name' => 'James K.', 'rating' => 5, 'text' => 'Fast turnaround and excellent research. Will definitely use this service again for my next essay.'],
                    ['name' => 'Emily R.', 'rating' => 5, 'text' => 'Professional service with great communication. My essay exceeded all expectations!'],
                ];
            @endphp

            @foreach($testimonials as $testimonial)
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center gap-1 mb-4">
                    @for($j = 1; $j <= $testimonial['rating']; $j++)
                        <i data-lucide="star" class="h-5 w-5 text-yellow-400 fill-current"></i>
                    @endfor
                </div>
                <p class="text-gray-700 mb-6 italic">"{{ $testimonial['text'] }}"</p>
                <div class="font-semibold text-gray-900">{{ $testimonial['name'] }}</div>
                <div class="text-sm text-gray-600">Verified Customer</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            @php
                $faqs = [
                    ['q' => 'Is your essay writing service legal?', 'a' => 'Yes, completely legal. We provide academic writing assistance and model papers for research purposes.'],
                    ['q' => 'Do you guarantee plagiarism-free essays?', 'a' => 'Absolutely! Every essay is written from scratch and comes with a free Turnitin plagiarism report.'],
                    ['q' => 'What if I need revisions?', 'a' => 'We offer unlimited free revisions within 14 days to ensure your complete satisfaction.'],
                    ['q' => 'How do I communicate with my writer?', 'a' => 'You can chat directly with your assigned writer through our secure messaging system.'],
                    ['q' => 'What payment methods do you accept?', 'a' => 'We accept M-Pesa, PayPal, PesaPal, and all major credit/debit cards.'],
                ];
            @endphp

            @foreach($faqs as $faq)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                    <span class="font-semibold text-gray-900">{{ $faq['q'] }}</span>
                    <i data-lucide="chevron-down" class="h-5 w-5 text-gray-500 transform transition-transform"></i>
                </button>
                <div class="faq-answer hidden px-6 py-4 bg-gray-50 text-gray-600">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Get Your Essay Written?</h2>
        <p class="text-xl text-blue-100 mb-8 leading-relaxed">
            Join thousands of satisfied students who trust us with their academic success
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center bg-white text-blue-600 px-10 py-5 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
            Order Your Essay Now
            <i data-lucide="arrow-right" class="ml-3 h-6 w-6"></i>
        </a>
        <p class="mt-6 text-blue-100 text-sm">
            <i data-lucide="shield-check" class="inline h-5 w-5 mr-2"></i>
            Money-back guarantee • 24/7 support • 100% confidential
        </p>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Price calculator
    function calculatePrice() {
        const level = parseFloat(document.getElementById('academicLevel').value);
        const pages = parseInt(document.getElementById('numPages').value);
        const deadline = parseFloat(document.getElementById('deadline').value);
        
        const total = (level * pages * deadline).toFixed(2);
        document.getElementById('totalPrice').textContent = total;
        
        // Update urgency text
        const urgencyTexts = {
            '1.0': 'Standard',
            '1.2': 'Normal',
            '1.4': 'Moderate Rush',
            '1.7': 'Rush',
            '2.0': 'Urgent',
            '2.5': 'Super Urgent'
        };
        document.getElementById('urgencyText').textContent = urgencyTexts[deadline.toString()] || 'Standard';
    }
    
    document.getElementById('academicLevel').addEventListener('change', calculatePrice);
    document.getElementById('numPages').addEventListener('input', calculatePrice);
    document.getElementById('deadline').addEventListener('change', calculatePrice);
    
    calculatePrice();
    
    // FAQ toggle
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('[data-lucide="chevron-down"]');
            
            answer.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
});
</script>

<style>
@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 3s ease-in-out infinite;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}

.bg-grid-pattern {
    background-image: linear-gradient(#e5e7eb 1px, transparent 1px),
                      linear-gradient(90deg, #e5e7eb 1px, transparent 1px);
    background-size: 20px 20px;
}
</style>
@endsection
