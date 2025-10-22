<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apex Scholars Nexus') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Framer Motion -->
    <script src="https://unpkg.com/framer-motion@11.0.0/dist/framer-motion.js"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased" x-data="{ openTab: null }">
    <div id="app">
        <!-- Header -->
        <header class="fixed w-full z-50 bg-white/95 backdrop-blur-sm shadow-sm transition-all duration-300" x-data="{ mobileMenuOpen: false, scrolled: false }" 
                x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
                :class="{ 'bg-white/98 shadow-md': scrolled }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex-shrink-0 transition-transform hover:scale-105">
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Apex Scholars</span>
                        </a>
                        <nav class="hidden md:ml-10 md:flex space-x-8">
                            <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200 relative group">
                                Home
                                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                            </a>
                            <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200 relative group">
                                About
                                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                            </a>
                            <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200 relative group">
                                Services
                                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                            </a>
                            <a href="{{ route('knowledge-resources.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200 relative group">
                                Knowledge Hub
                                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                            </a>
                            <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200 relative group">
                                Pricing
                                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                            </a>
                            <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200 relative group">
                                Contact
                                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                            </a>
                        </nav>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">Sign In</a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">Get Started</a>
                        @else
                            <a href="{{ route('courses.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">My Courses</a>
                            @if(auth()->user()->hasRole('expert'))
                                @php
                                    $expertProfile = auth()->user()->expertProfile;
                                @endphp
                                <a href="/dashboard/expert" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">Dashboard</a>
                            @else
                                <a href="/admin" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">Dashboard</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">Logout</button>
                            </form>
                        @endguest
                    </div>
                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center md:hidden">
                        <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none transition-colors duration-200">
                            <i data-lucide="menu" x-show="!mobileMenuOpen" class="h-6 w-6"></i>
                            <i data-lucide="x" x-show="mobileMenuOpen" class="h-6 w-6"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div class="md:hidden" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                    <a href="{{ url('/') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Home</a>
                    <a href="{{ route('about') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">About</a>
                    <a href="{{ route('services.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Services</a>
                    <a href="{{ route('knowledge-resources.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Knowledge Hub</a>
                    <a href="{{ route('pricing') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Pricing</a>
                    <a href="{{ route('contact') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Contact</a>
                    @guest
                        <div class="pt-4 pb-3 border-t border-gray-200">
                            <div class="space-y-1">
                                <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Sign In</a>
                                <a href="{{ route('register') }}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-md text-base font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 mx-3">Get Started</a>
                            </div>
                        </div>
                    @else
                        <div class="pt-4 pb-3 border-t border-gray-200">
                            <div class="space-y-1">
                                @if(auth()->user()->hasRole('expert'))
                                    <a href="/dashboard/expert" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Dashboard</a>
                                @else
                                    <a href="/admin" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="pt-16">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Apex Scholars</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Empowering students with quality academic support and resources to achieve their educational goals and unlock their full potential.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                <i data-lucide="facebook" class="h-5 w-5"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                <i data-lucide="twitter" class="h-5 w-5"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                <i data-lucide="linkedin" class="h-5 w-5"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                <i data-lucide="instagram" class="h-5 w-5"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-6">Quick Links</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('services.index') }}" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Services</a></li>
                            <li><a href="{{ route('pricing') }}" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Pricing</a></li>
                            <li><a href="{{ route('experts') }}" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Experts</a></li>
                            <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">About Us</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-6">Support</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Contact Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">FAQs</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Privacy Policy</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Terms of Service</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-6">Newsletter</h4>
                        <p class="text-gray-400 text-sm mb-4 leading-relaxed">Subscribe to our newsletter for the latest updates and offers.</p>
                        <form class="flex" x-data="{ email: '' }">
                            <input type="email" x-model="email" placeholder="Your email" class="px-4 py-2 w-full rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 text-sm">
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-4 py-2 rounded-r-md transition-all duration-200">
                                <i data-lucide="send" class="h-4 w-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-gray-800">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-center text-gray-400 text-sm">&copy; {{ date('Y') }} Apex Scholars Nexus. All rights reserved.</p>
                        <div class="flex space-x-6 mt-4 md:mt-0">
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Privacy</a>
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Terms</a>
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Cookies</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Initialize Lucide Icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Add scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                    }
                });
            }, observerOptions);
            
            // Observe elements with animation class
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
        
        // Add custom CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-fade-in-up {
                animation: fadeInUp 0.6s ease-out forwards;
            }
            
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease-out;
            }
            
            .gradient-text {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
