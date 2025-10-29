@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-on-scroll">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Get in <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Touch</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Have questions about our services? Need academic support? Our team is here to help you succeed in your educational journey.
            </p>
        </div>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Contact Form -->
            <div class="animate-on-scroll">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Fill out the form below and we'll get back to you within 24 hours. For urgent matters, please call us directly.
                </p>
                
                <form class="space-y-6" x-data="contactForm()" @submit.prevent="submitForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" id="first_name" name="first_name" x-model="form.first_name" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" x-model="form.last_name" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" id="email" name="email" x-model="form.email" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" x-model="form.phone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <select id="subject" name="subject" x-model="form.subject" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="academic_support">Academic Support</option>
                            <option value="expert_application">Expert Application</option>
                            <option value="technical_support">Technical Support</option>
                            <option value="billing">Billing & Payments</option>
                            <option value="partnership">Partnership Opportunities</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea id="message" name="message" rows="6" x-model="form.message" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            placeholder="Tell us how we can help you..."></textarea>
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" id="newsletter" name="newsletter" x-model="form.newsletter"
                            class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="newsletter" class="ml-3 text-sm text-gray-600">
                            I would like to receive updates and newsletters from Scholars Quiver
                        </label>
                    </div>
                    
                    <button type="submit" :disabled="loading"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 flex items-center justify-center transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">
                            <i data-lucide="send" class="h-5 w-5 mr-2"></i>
                            Send Message
                        </span>
                        <span x-show="loading" class="flex items-center">
                            <i data-lucide="loader" class="h-5 w-5 mr-2 animate-spin"></i>
                            Sending...
                        </span>
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div class="animate-on-scroll">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Reach out to us through any of these channels. We're available 24/7 to support your academic needs.
                </p>
                
                <div class="space-y-8">
                    <div class="flex items-start group">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 mr-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="map-pin" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Office Address</h3>
                            <p class="text-gray-600 leading-relaxed">
                                123 Academic Street<br>
                                Education District<br>
                                Nairobi, Kenya 00100
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 mr-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="phone" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone Numbers</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Main: <a href="tel:+254700123456" class="text-blue-600 hover:text-blue-700">+254 700 123 456</a><br>
                                Support: <a href="tel:+254700123457" class="text-blue-600 hover:text-blue-700">+254 700 123 457</a><br>
                                WhatsApp: <a href="https://wa.me/254700123458" class="text-blue-600 hover:text-blue-700">+254 700 123 458</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 mr-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="mail" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Addresses</h3>
                            <p class="text-gray-600 leading-relaxed">
                                General: <a href="mailto:info@apexscholars.com" class="text-blue-600 hover:text-blue-700">info@apexscholars.com</a><br>
                                Support: <a href="mailto:support@apexscholars.com" class="text-blue-600 hover:text-blue-700">support@apexscholars.com</a><br>
                                Experts: <a href="mailto:experts@apexscholars.com" class="text-blue-600 hover:text-blue-700">experts@apexscholars.com</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start group">
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-4 mr-6 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="clock" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Business Hours</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Monday - Friday: 8:00 AM - 8:00 PM<br>
                                Saturday: 9:00 AM - 6:00 PM<br>
                                Sunday: 10:00 AM - 4:00 PM<br>
                                <span class="text-blue-600 font-medium">24/7 Online Support</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media Links -->
                <div class="mt-12">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-3 rounded-xl hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i data-lucide="facebook" class="h-5 w-5"></i>
                        </a>
                        <a href="#" class="bg-gradient-to-br from-blue-400 to-blue-500 text-white p-3 rounded-xl hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i data-lucide="twitter" class="h-5 w-5"></i>
                        </a>
                        <a href="#" class="bg-gradient-to-br from-blue-700 to-blue-800 text-white p-3 rounded-xl hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i data-lucide="linkedin" class="h-5 w-5"></i>
                        </a>
                        <a href="#" class="bg-gradient-to-br from-pink-500 to-pink-600 text-white p-3 rounded-xl hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i data-lucide="instagram" class="h-5 w-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-gray-600 leading-relaxed">
                Quick answers to common questions. Can't find what you're looking for? Contact us directly.
            </p>
        </div>
        
        <div class="space-y-6" x-data="{ openFaq: null }">
            @php
                $faqs = [
                    [
                        'question' => 'How quickly can I get help with my assignment?',
                        'answer' => 'Most assignments receive expert responses within 2-4 hours. For urgent requests, we offer priority support with guaranteed 1-hour response times.'
                    ],
                    [
                        'question' => 'What subjects do you cover?',
                        'answer' => 'We cover over 50 academic subjects including Mathematics, Sciences, Literature, Business, Engineering, and more. Our experts span all major academic disciplines.'
                    ],
                    [
                        'question' => 'How do I become an expert on your platform?',
                        'answer' => 'Apply through our expert application process. You\'ll need to provide credentials, complete a subject assessment, and undergo our verification process. Qualified experts earn competitive rates.'
                    ],
                    [
                        'question' => 'Is my personal information secure?',
                        'answer' => 'Yes, we use industry-standard encryption and security measures to protect all user data. We never share personal information with third parties without consent.'
                    ],
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept major credit cards, PayPal, M-Pesa, and bank transfers. All transactions are secure and encrypted for your protection.'
                    ]
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-on-scroll">
                    <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}" 
                            class="w-full px-6 py-4 text-left focus:outline-none hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $faq['question'] }}</h3>
                            <i data-lucide="chevron-down" class="h-5 w-5 text-gray-500 transform transition-transform duration-200"
                               :class="{ 'rotate-180': openFaq === {{ $index }} }"></i>
                        </div>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                         class="px-6 pb-4 overflow-hidden">
                        <p class="text-gray-600 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Support Channels -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Multiple Ways to Get Support</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Choose the support channel that works best for you. We're committed to providing excellent customer service.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 border border-gray-200 rounded-2xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll group">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="message-circle" class="h-10 w-10 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Live Chat</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Get instant help through our live chat system. Available 24/7 for immediate assistance.
                </p>
                <button class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 font-semibold">
                    Start Chat
                </button>
            </div>
            
            <div class="text-center p-8 border border-gray-200 rounded-2xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll group">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="phone-call" class="h-10 w-10 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Phone Support</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Speak directly with our support team for complex issues or detailed discussions.
                </p>
                <a href="tel:+254700123456" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 transform hover:scale-105 font-semibold inline-block">
                    Call Now
                </a>
            </div>
            
            <div class="text-center p-8 border border-gray-200 rounded-2xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-on-scroll group">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="video" class="h-10 w-10 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Video Call</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Schedule a video call for personalized support and detailed explanations.
                </p>
                <button class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 transform hover:scale-105 font-semibold">
                    Schedule Call
                </button>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-6">Ready to Start Your Academic Journey?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto leading-relaxed">
            Don't let academic challenges hold you back. Join thousands of students who have achieved success with our support.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105">
                Get Started Today
            </a>
            <a href="{{ route('services.index') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-colors duration-300 transform hover:scale-105">
                View Our Services
            </a>
        </div>
    </div>
</section>

<script>
function contactForm() {
    return {
        form: {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            subject: '',
            message: '',
            newsletter: false
        },
        loading: false,
        
        submitForm() {
            this.loading = true;
            
            // Simulate form submission
            setTimeout(() => {
                this.loading = false;
                // Show success message
                alert('Thank you for your message! We\'ll get back to you within 24 hours.');
                // Reset form
                this.form = {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    subject: '',
                    message: '',
                    newsletter: false
                };
            }, 2000);
        }
    }
}
</script>
@endsection
