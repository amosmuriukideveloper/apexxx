@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                <i data-lucide="user-plus" class="h-6 w-6 text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Join Apex Scholars</h2>
            <p class="text-gray-600">Choose your role and start your academic journey</p>
        </div>

        <!-- Role Selection Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Student Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer border-2 border-transparent hover:border-blue-500"
                 onclick="window.location.href='{{ route('register.student') }}'">
                <div class="p-6 text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="graduation-cap" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Student</h3>
                    <p class="text-gray-600 text-sm mb-4">Access tutoring, writing services, and academic resources</p>
                    <div class="space-y-2 text-xs text-gray-500">
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Project submissions</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Tutoring sessions</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Resource access</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expert Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer border-2 border-transparent hover:border-purple-500"
                 onclick="window.location.href='{{ route('register.expert') }}'">
                <div class="p-6 text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="award" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Expert</h3>
                    <p class="text-gray-600 text-sm mb-4">Provide writing and research services to students</p>
                    <div class="space-y-2 text-xs text-gray-500">
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Accept projects</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Earn income</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Build portfolio</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutor Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer border-2 border-transparent hover:border-green-500"
                 onclick="window.location.href='{{ route('register.tutor') }}'">
                <div class="p-6 text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="users" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tutor</h3>
                    <p class="text-gray-600 text-sm mb-4">Offer one-on-one and group tutoring sessions</p>
                    <div class="space-y-2 text-xs text-gray-500">
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Schedule sessions</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Set your rates</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Track earnings</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Creator Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer border-2 border-transparent hover:border-orange-500"
                 onclick="window.location.href='{{ route('register.creator') }}'">
                <div class="p-6 text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="edit" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Creator</h3>
                    <p class="text-gray-600 text-sm mb-4">Create and sell educational content and courses</p>
                    <div class="space-y-2 text-xs text-gray-500">
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Upload content</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Set pricing</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i data-lucide="check" class="h-3 w-3 mr-1 text-green-500"></i>
                            <span>Earn royalties</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Registration Form -->
        <div class="bg-white rounded-xl shadow-xl p-8 max-w-md mx-auto">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Quick Registration</h3>
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror" 
                               placeholder="Enter your full name" value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror" 
                               placeholder="Enter your email" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">I want to join as</label>
                    <select id="role" name="role" required 
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('role') border-red-500 @enderror">
                        <option value="">Select your role</option>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="expert" {{ old('role') == 'expert' ? 'selected' : '' }}>Expert</option>
                        <option value="tutor" {{ old('role') == 'tutor' ? 'selected' : '' }}>Tutor</option>
                        <option value="content_creator" {{ old('role') == 'content_creator' ? 'selected' : '' }}>Content Creator</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
                               placeholder="Create a password">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               placeholder="Confirm your password">
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="flex items-center">
                            <i data-lucide="user-plus" class="h-4 w-4 mr-2"></i>
                            Create Account
                        </span>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection
