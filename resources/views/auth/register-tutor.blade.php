@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-4">
                <i data-lucide="users" class="h-6 w-6 text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Join as Tutor</h2>
            <p class="text-gray-600">Offer one-on-one and group tutoring sessions</p>
        </div>

        <div class="bg-white rounded-xl shadow-xl p-8">
            <form method="POST" action="{{ route('register.tutor') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input id="name" name="name" type="text" autocomplete="name" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror" 
                                   placeholder="Enter your full name" value="{{ old('name') }}">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror" 
                                   placeholder="Enter your email" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teaching Experience -->
                    <div>
                        <label for="teaching_experience" class="block text-sm font-medium text-gray-700 mb-2">Teaching Experience (Years)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input id="teaching_experience" name="teaching_experience" type="number" min="0" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('teaching_experience') border-red-500 @enderror" 
                                   placeholder="0" value="{{ old('teaching_experience') }}">
                        </div>
                        @error('teaching_experience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hourly Rate -->
                    <div>
                        <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate ($)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="dollar-sign" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input id="hourly_rate" name="hourly_rate" type="number" min="0" step="0.01" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('hourly_rate') border-red-500 @enderror" 
                                   placeholder="25.00" value="{{ old('hourly_rate') }}">
                        </div>
                        @error('hourly_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subjects -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subjects You Can Tutor</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-3">
                            @php
                                $subjects = [
                                    'mathematics' => 'Mathematics',
                                    'physics' => 'Physics',
                                    'chemistry' => 'Chemistry',
                                    'biology' => 'Biology',
                                    'english' => 'English',
                                    'history' => 'History',
                                    'geography' => 'Geography',
                                    'economics' => 'Economics',
                                    'business' => 'Business Studies',
                                    'computer_science' => 'Computer Science',
                                    'psychology' => 'Psychology',
                                    'sociology' => 'Sociology',
                                    'philosophy' => 'Philosophy',
                                    'statistics' => 'Statistics',
                                    'accounting' => 'Accounting',
                                    'finance' => 'Finance'
                                ];
                            @endphp
                            @foreach($subjects as $key => $label)
                                <label class="flex items-center">
                                    <input type="checkbox" name="subjects[]" value="{{ $key }}" 
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array($key, old('subjects', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('subjects')
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
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
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
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" 
                                   placeholder="Confirm your password">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="flex items-center">
                            <i data-lucide="users" class="h-4 w-4 mr-2"></i>
                            Create Tutor Account
                        </span>
                    </button>
                </div>

                <!-- Back to General Registration -->
                <div class="text-center">
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                        ← Back to role selection
                    </a>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500 transition-colors duration-200">
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
