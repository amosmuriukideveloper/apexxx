@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                <i data-lucide="award" class="h-6 w-6 text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Join as Expert</h2>
            <p class="text-gray-600">Provide writing and research services to students</p>
        </div>

        <div class="bg-white rounded-xl shadow-xl p-8">
            <form method="POST" action="{{ route('register.expert') }}" class="space-y-6">
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
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror" 
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
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror" 
                                   placeholder="Enter your email" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Experience Years -->
                    <div>
                        <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input id="experience_years" name="experience_years" type="number" min="0" required 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('experience_years') border-red-500 @enderror" 
                                   placeholder="0" value="{{ old('experience_years') }}">
                        </div>
                        @error('experience_years')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expertise Areas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expertise Areas</label>
                        <div class="space-y-2 max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-3">
                            @php
                                $expertiseAreas = [
                                    'academic_writing' => 'Academic Writing',
                                    'research_papers' => 'Research Papers',
                                    'essays' => 'Essays',
                                    'dissertations' => 'Dissertations',
                                    'thesis' => 'Thesis',
                                    'literature_review' => 'Literature Review',
                                    'case_studies' => 'Case Studies',
                                    'lab_reports' => 'Lab Reports',
                                    'business_writing' => 'Business Writing',
                                    'technical_writing' => 'Technical Writing'
                                ];
                            @endphp
                            @foreach($expertiseAreas as $key => $label)
                                <label class="flex items-center">
                                    <input type="checkbox" name="expertise_areas[]" value="{{ $key }}" 
                                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                           {{ in_array($key, old('expertise_areas', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('expertise_areas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Qualifications -->
                    <div class="md:col-span-2">
                        <label for="qualifications" class="block text-sm font-medium text-gray-700 mb-2">Qualifications & Education</label>
                        <textarea id="qualifications" name="qualifications" rows="3" required 
                                  class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('qualifications') border-red-500 @enderror" 
                                  placeholder="Describe your educational background, degrees, certifications, etc.">{{ old('qualifications') }}</textarea>
                        @error('qualifications')
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
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
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
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" 
                                   placeholder="Confirm your password">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-[1.02]">
                        <span class="flex items-center">
                            <i data-lucide="award" class="h-4 w-4 mr-2"></i>
                            Create Expert Account
                        </span>
                    </button>
                </div>

                <!-- Back to General Registration -->
                <div class="text-center">
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200">
                        ← Back to role selection
                    </a>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500 transition-colors duration-200">
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
