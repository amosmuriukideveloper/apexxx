<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Apex Scholars</title>
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
                            <h1 class="text-4xl font-bold mb-4">Join Us Today!</h1>
                            <p class="text-lg text-white/90">Start your journey to academic excellence</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-check text-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-1">Quick Setup</h3>
                                    <p class="text-white/80 text-sm">Create your account in just a few steps</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-certificate text-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-1">Verified Experts</h3>
                                    <p class="text-white/80 text-sm">Work with certified professionals</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-lock text-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-1">Secure & Private</h3>
                                    <p class="text-white/80 text-sm">Your data is protected with encryption</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-12 p-6 bg-white/10 rounded-lg backdrop-blur-sm">
                            <p class="text-sm font-semibold mb-2">🎓 Special Offer</p>
                            <p class="text-white/90 text-sm">Get 20% off your first project when you register today!</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Register Selection -->
                <div class="md:w-1/2 p-12">
                    <div class="max-w-md mx-auto">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                            <p class="text-gray-600">Select your account type to get started</p>
                        </div>
                        
                        <!-- Role Selection Buttons -->
                        <div class="space-y-4 mb-8">
                            <a href="{{ route('filament.student.auth.register') }}" 
                               class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-5 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-graduate text-2xl mr-3"></i>
                                        <div class="text-left">
                                            <div class="font-bold">Student Account</div>
                                            <div class="text-sm text-blue-100">Get help with assignments & courses</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('filament.expert.auth.register') }}" 
                               class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-5 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tie text-2xl mr-3"></i>
                                        <div class="text-left">
                                            <div class="font-bold">Expert Account</div>
                                            <div class="text-sm text-purple-100">Help students with projects</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('filament.tutor.auth.register') }}" 
                               class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-5 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-chalkboard-teacher text-2xl mr-3"></i>
                                        <div class="text-left">
                                            <div class="font-bold">Tutor Account</div>
                                            <div class="text-sm text-indigo-100">Offer tutoring sessions</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('filament.creator.auth.register') }}" 
                               class="block w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-5 px-6 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-palette text-2xl mr-3"></i>
                                        <div class="text-left">
                                            <div class="font-bold">Creator Account</div>
                                            <div class="text-sm text-pink-100">Create and sell courses</div>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Login Link -->
                        <div class="text-center pt-6 border-t border-gray-200">
                            <p class="text-gray-600">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    Sign in here
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
