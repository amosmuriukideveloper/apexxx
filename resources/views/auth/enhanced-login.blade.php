<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Apex Scholars</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-6xl mx-4 my-8">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Left Side - Image/Brand Section -->
                <div class="md:w-1/2 gradient-bg hero-pattern relative p-12 flex flex-col justify-center items-center text-white">
                    <div class="relative z-10">
                        <div class="mb-8">
                            <h1 class="text-4xl font-bold mb-4">Welcome Back!</h1>
                            <p class="text-lg text-white/90">Access your personalized learning dashboard</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-graduation-cap text-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-1">Expert Guidance</h3>
                                    <p class="text-white/80 text-sm">Access top-tier academic support 24/7</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-book-open text-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-1">Quality Resources</h3>
                                    <p class="text-white/80 text-sm">Premium study materials and courses</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-chart-line text-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-1">Track Progress</h3>
                                    <p class="text-white/80 text-sm">Monitor your academic journey</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-12">
                            <p class="text-sm text-white/70">Trusted by over 10,000+ students worldwide</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="md:w-1/2 p-12">
                    <div class="max-w-md mx-auto">
                        <!-- Logo -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Sign In</h2>
                            <p class="text-gray-600">Choose your account type to continue</p>
                        </div>
                        
                        <!-- Role Selection Buttons -->
                        <div class="space-y-4 mb-8">
                            <a href="{{ route('filament.student.auth.login') }}" 
                               class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                                <i class="fas fa-user-graduate mr-2"></i>
                                Student Portal
                            </a>
                            
                            <a href="{{ route('filament.expert.auth.login') }}" 
                               class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                                <i class="fas fa-user-tie mr-2"></i>
                                Expert Portal
                            </a>
                            
                            <a href="{{ route('filament.tutor.auth.login') }}" 
                               class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                Tutor Portal
                            </a>
                            
                            <a href="{{ route('filament.creator.auth.login') }}" 
                               class="block w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                                <i class="fas fa-palette mr-2"></i>
                                Creator Portal
                            </a>
                            
                            <a href="{{ route('filament.platform.auth.login') }}" 
                               class="block w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl text-center">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Admin Portal
                            </a>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center pt-6 border-t border-gray-200">
                            <p class="text-gray-600">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    Register here
                                </a>
                            </p>
                        </div>
                        
                        <!-- Back to Home -->
                        <div class="text-center mt-6">
                            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
