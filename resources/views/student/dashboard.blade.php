@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Role:</span>
                        <span class="text-sm font-medium text-blue-600 ml-1">Student</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i data-lucide="book-open" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Projects</p>
                        <p class="text-2xl font-bold text-gray-900">3</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i data-lucide="check-circle" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">12</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i data-lucide="users" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tutoring Sessions</p>
                        <p class="text-2xl font-bold text-gray-900">8</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i data-lucide="star" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Average Rating</p>
                        <p class="text-2xl font-bold text-gray-900">4.8</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Active Projects -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Active Projects</h2>
                        <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Research Paper: Climate Change</h3>
                                    <p class="text-sm text-gray-600 mt-1">Environmental Science • Due: Dec 15, 2024</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">75%</span>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">In Progress</span>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Essay: Modern Literature Analysis</h3>
                                    <p class="text-sm text-gray-600 mt-1">English Literature • Due: Dec 20, 2024</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">45%</span>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Assigned</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Recent Activity</h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Project "Statistics Assignment" completed</p>
                                <p class="text-xs text-gray-500">2 hours ago</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i data-lucide="message-circle" class="h-4 w-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">New message from Expert Sarah Johnson</p>
                                <p class="text-xs text-gray-500">5 hours ago</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i data-lucide="calendar" class="h-4 w-4 text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Tutoring session scheduled for tomorrow</p>
                                <p class="text-xs text-gray-500">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h2>
                    <div class="space-y-3">
                        <button class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                            <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                            Submit New Project
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="users" class="h-4 w-4 mr-2"></i>
                            Book Tutoring Session
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="book" class="h-4 w-4 mr-2"></i>
                            Browse Resources
                        </button>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Upcoming Deadlines</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Climate Change Paper</p>
                                <p class="text-xs text-gray-600">Environmental Science</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-red-600">Dec 15</p>
                                <p class="text-xs text-red-500">3 days left</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Literature Essay</p>
                                <p class="text-xs text-gray-600">English Literature</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-yellow-600">Dec 20</p>
                                <p class="text-xs text-yellow-500">8 days left</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Need Help?</h2>
                    <p class="text-sm text-gray-600 mb-4">Our support team is here to assist you with any questions.</p>
                    <button class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                        <i data-lucide="help-circle" class="h-4 w-4 mr-2"></i>
                        Contact Support
                    </button>
                </div>
            </div>
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
