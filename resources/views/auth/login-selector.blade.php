<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Scholars Quiver</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Welcome to Scholars Quiver</h1>
            <p class="text-gray-600">Select your role to continue to your dashboard</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Student Login -->
            <a href="/student/login" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-8 text-center group">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Student</h3>
                <p class="text-gray-600 text-sm">Access your projects, courses, and assignments</p>
            </a>

            <!-- Expert Login -->
            <a href="/expert/login" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-8 text-center group">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Expert</h3>
                <p class="text-gray-600 text-sm">Manage your assigned projects and deliverables</p>
            </a>

            <!-- Tutor Login -->
            <a href="/tutor/login" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-8 text-center group">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tutor</h3>
                <p class="text-gray-600 text-sm">Manage tutoring sessions and schedules</p>
            </a>

            <!-- Content Creator Login -->
            <a href="/creator/login" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-8 text-center group">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Content Creator</h3>
                <p class="text-gray-600 text-sm">Create and manage your courses</p>
            </a>

            <!-- Admin/Staff Login -->
            <a href="/platform/login" class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-8 text-center group md:col-span-2">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Admin / Staff</h3>
                <p class="text-gray-600 text-sm">Platform administration and management</p>
            </a>
        </div>

        <div class="text-center mt-8">
            <p class="text-gray-600">
                Don't have an account? 
                <a href="/register" class="text-blue-600 hover:text-blue-700 font-semibold">Register here</a>
            </p>
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm mt-2 inline-block">← Back to Home</a>
        </div>
    </div>
</body>
</html>
