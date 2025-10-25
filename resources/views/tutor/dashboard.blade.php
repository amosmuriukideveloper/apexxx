@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tutor Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Role:</span>
                        <span class="text-sm font-medium text-green-600 ml-1">Tutor</span>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Rating:</span>
                        <span class="text-sm font-medium text-yellow-600 ml-1">4.7 ⭐</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i data-lucide="calendar" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Week</p>
                        <p class="text-2xl font-bold text-gray-900">12</p>
                        <p class="text-xs text-gray-500">Sessions</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i data-lucide="users" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Students</p>
                        <p class="text-2xl font-bold text-gray-900">28</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i data-lucide="dollar-sign" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-gray-900">$1,850</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i data-lucide="clock" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hours Taught</p>
                        <p class="text-2xl font-bold text-gray-900">156</p>
                        <p class="text-xs text-gray-500">This month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Today's Schedule -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Today's Schedule</h2>
                        <a href="#" class="text-green-600 hover:text-green-700 text-sm font-medium">View Calendar</a>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="video" class="h-6 w-6 text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-medium text-gray-900">Mathematics - Calculus</h3>
                                <p class="text-sm text-gray-600">Student: Alex Johnson • 2:00 PM - 3:00 PM</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-600 text-white text-xs rounded-full hover:bg-green-700">Join</button>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200">Reschedule</button>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="users" class="h-6 w-6 text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-medium text-gray-900">Physics - Group Session</h3>
                                <p class="text-sm text-gray-600">3 Students • 4:00 PM - 5:30 PM</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded-full hover:bg-blue-700">Join</button>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200">Details</button>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border-l-4 border-gray-300">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="book" class="h-6 w-6 text-gray-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-medium text-gray-900">Chemistry - Lab Review</h3>
                                <p class="text-sm text-gray-600">Student: Maria Garcia • 6:00 PM - 7:00 PM</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-gray-600 text-white text-xs rounded-full hover:bg-gray-700">Prepare</button>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200">Message</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Sessions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Recent Sessions</h2>
                        <a href="#" class="text-green-600 hover:text-green-700 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i data-lucide="check" class="h-5 w-5 text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-medium text-gray-900">Mathematics - Algebra</h3>
                                    <p class="text-sm text-gray-600">Student: John Smith • Yesterday, 3:00 PM</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-green-600 font-medium">Completed</span>
                                <div class="flex text-yellow-400 text-sm">⭐⭐⭐⭐⭐</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i data-lucide="check" class="h-5 w-5 text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-medium text-gray-900">Physics - Mechanics</h3>
                                    <p class="text-sm text-gray-600">Student: Sarah Wilson • Dec 10, 2:00 PM</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-green-600 font-medium">Completed</span>
                                <div class="flex text-yellow-400 text-sm">⭐⭐⭐⭐⭐</div>
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
                        <button class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg hover:from-green-700 hover:to-blue-700 transition-all duration-200">
                            <i data-lucide="calendar-plus" class="h-4 w-4 mr-2"></i>
                            Set Availability
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="video" class="h-4 w-4 mr-2"></i>
                            Start Session
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="message-circle" class="h-4 w-4 mr-2"></i>
                            Messages
                        </button>
                    </div>
                </div>

                <!-- Earnings -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Earnings</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">This Week</span>
                            <span class="text-sm font-medium text-gray-900">$420</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">This Month</span>
                            <span class="text-sm font-medium text-gray-900">$1,850</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Hourly Rate</span>
                            <span class="text-sm font-medium text-gray-900">$35/hr</span>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Available Balance</span>
                            <span class="text-lg font-bold text-green-600">$890</span>
                        </div>
                        <button class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Withdraw Funds
                        </button>
                    </div>
                </div>

                <!-- Student Requests -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">New Requests</h2>
                    <div class="space-y-4">
                        <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Mathematics Help</h3>
                                    <p class="text-sm text-gray-600">Student: Emma Davis</p>
                                    <p class="text-xs text-gray-500 mt-1">Calculus • Preferred: Weekends</p>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <button class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Accept</button>
                                    <button class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Decline</button>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Physics Group Session</h3>
                                    <p class="text-sm text-gray-600">3 Students</p>
                                    <p class="text-xs text-gray-500 mt-1">Mechanics • Preferred: Evenings</p>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <button class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Accept</button>
                                    <button class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Decline</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
