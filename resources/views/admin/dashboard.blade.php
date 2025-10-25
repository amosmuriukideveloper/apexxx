@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Role:</span>
                        <span class="text-sm font-medium text-red-600 ml-1">Admin</span>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">System Status:</span>
                        <span class="text-sm font-medium text-green-600 ml-1">Operational</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i data-lucide="users" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">2,847</p>
                        <p class="text-xs text-green-600">+12% this month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i data-lucide="briefcase" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Projects</p>
                        <p class="text-2xl font-bold text-gray-900">156</p>
                        <p class="text-xs text-blue-600">23 pending review</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i data-lucide="dollar-sign" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">$45,680</p>
                        <p class="text-xs text-green-600">+8% this month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i data-lucide="alert-triangle" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Issues</p>
                        <p class="text-2xl font-bold text-gray-900">7</p>
                        <p class="text-xs text-red-600">3 high priority</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Pending Approvals -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Pending Approvals</h2>
                        <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                            <div>
                                <h3 class="font-medium text-gray-900">Project: Advanced Statistics Analysis</h3>
                                <p class="text-sm text-gray-600">Expert: Dr. Sarah Johnson • Student: Mike Chen</p>
                                <p class="text-xs text-gray-500 mt-1">Submitted 2 hours ago • $180</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-600 text-white text-xs rounded-full hover:bg-green-700">Approve</button>
                                <button class="px-3 py-1 bg-red-600 text-white text-xs rounded-full hover:bg-red-700">Reject</button>
                                <button class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-full hover:bg-gray-300">Review</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                            <div>
                                <h3 class="font-medium text-gray-900">Content: Physics Lab Manual v2.0</h3>
                                <p class="text-sm text-gray-600">Creator: Prof. David Wilson</p>
                                <p class="text-xs text-gray-500 mt-1">Submitted 5 hours ago • $29.99</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-600 text-white text-xs rounded-full hover:bg-green-700">Approve</button>
                                <button class="px-3 py-1 bg-red-600 text-white text-xs rounded-full hover:bg-red-700">Reject</button>
                                <button class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-full hover:bg-gray-300">Preview</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border-l-4 border-purple-400">
                            <div>
                                <h3 class="font-medium text-gray-900">Expert Application: Maria Rodriguez</h3>
                                <p class="text-sm text-gray-600">Specialization: Business & Economics</p>
                                <p class="text-xs text-gray-500 mt-1">Applied 1 day ago • 5 years experience</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-600 text-white text-xs rounded-full hover:bg-green-700">Approve</button>
                                <button class="px-3 py-1 bg-red-600 text-white text-xs rounded-full hover:bg-red-700">Reject</button>
                                <button class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-full hover:bg-gray-300">Interview</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">System Activity</h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i data-lucide="user-plus" class="h-4 w-4 text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">New student registration: John Smith</p>
                                <p class="text-xs text-gray-500">15 minutes ago</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i data-lucide="check-circle" class="h-4 w-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Project completed: "Marketing Research Paper"</p>
                                <p class="text-xs text-gray-500">1 hour ago • $150 transaction</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i data-lucide="upload" class="h-4 w-4 text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">New content uploaded: "Chemistry Basics Course"</p>
                                <p class="text-xs text-gray-500">3 hours ago • Pending review</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <i data-lucide="alert-circle" class="h-4 w-4 text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Payment dispute reported by student</p>
                                <p class="text-xs text-gray-500">5 hours ago • Requires attention</p>
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
                            <i data-lucide="users" class="h-4 w-4 mr-2"></i>
                            Manage Users
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="briefcase" class="h-4 w-4 mr-2"></i>
                            Review Projects
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="bar-chart" class="h-4 w-4 mr-2"></i>
                            View Analytics
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="settings" class="h-4 w-4 mr-2"></i>
                            System Settings
                        </button>
                    </div>
                </div>

                <!-- User Statistics -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">User Statistics</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Students</span>
                            <span class="text-sm font-medium text-blue-600">1,847</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Experts</span>
                            <span class="text-sm font-medium text-purple-600">234</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tutors</span>
                            <span class="text-sm font-medium text-green-600">156</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Creators</span>
                            <span class="text-sm font-medium text-orange-600">89</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Admins</span>
                            <span class="text-sm font-medium text-red-600">12</span>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">System Health</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Server Status</span>
                            <span class="flex items-center text-sm text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Online
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Database</span>
                            <span class="flex items-center text-sm text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Healthy
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Payment System</span>
                            <span class="flex items-center text-sm text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Active
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">File Storage</span>
                            <span class="flex items-center text-sm text-yellow-600">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                85% Full
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recent Issues -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Issues</h2>
                    <div class="space-y-3">
                        <div class="p-3 bg-red-50 rounded-lg border-l-4 border-red-400">
                            <h3 class="text-sm font-medium text-red-800">Payment Failed</h3>
                            <p class="text-xs text-red-600">Student ID: 1847 • $150</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>

                        <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                            <h3 class="text-sm font-medium text-yellow-800">Content Flagged</h3>
                            <p class="text-xs text-yellow-600">Content ID: 234 • Plagiarism</p>
                            <p class="text-xs text-gray-500">5 hours ago</p>
                        </div>

                        <div class="p-3 bg-orange-50 rounded-lg border-l-4 border-orange-400">
                            <h3 class="text-sm font-medium text-orange-800">Server Load High</h3>
                            <p class="text-xs text-orange-600">CPU: 89% • Memory: 76%</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
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
