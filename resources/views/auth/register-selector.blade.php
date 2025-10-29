<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Scholars Quiver</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-5xl w-full">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Join Scholars Quiver</h1>
            <p class="text-gray-600">Choose your path and start your journey with us</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Student Registration -->
            <a href="/student/register" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center group">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">I'm a Student</h3>
                <p class="text-gray-600 text-sm mb-4">Get help with projects and assignments</p>
                <span class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium group-hover:bg-blue-700 transition-colors">Register Now</span>
            </a>

            <!-- Expert Registration -->
            <a href="/expert/register" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center group">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">I'm an Expert</h3>
                <p class="text-gray-600 text-sm mb-4">Help students with academic projects</p>
                <span class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium group-hover:bg-indigo-700 transition-colors">Apply Now</span>
            </a>

            <!-- Tutor Registration -->
            <a href="/tutor/register" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center group">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">I'm a Tutor</h3>
                <p class="text-gray-600 text-sm mb-4">Provide one-on-one tutoring</p>
                <span class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium group-hover:bg-green-700 transition-colors">Apply Now</span>
            </a>

            <!-- Content Creator Registration -->
            <a href="/creator/register" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center group">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">I'm a Creator</h3>
                <p class="text-gray-600 text-sm mb-4">Create and sell courses</p>
                <span class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium group-hover:bg-purple-700 transition-colors">Apply Now</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Why Join Scholars Quiver?</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-gray-900">Quality Assured</h4>
                        <p class="text-sm text-gray-600">Top-rated experts and tutors</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-gray-900">Secure Payments</h4>
                        <p class="text-sm text-gray-600">Safe and reliable transactions</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-gray-900">24/7 Support</h4>
                        <p class="text-sm text-gray-600">We're here to help anytime</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="text-gray-600">
                Already have an account? 
                <a href="/login" class="text-blue-600 hover:text-blue-700 font-semibold">Sign in here</a>
            </p>
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm mt-2 inline-block">← Back to Home</a>
        </div>
    </div>
</body>
</html>
