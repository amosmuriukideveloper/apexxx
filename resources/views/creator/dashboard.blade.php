@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-purple-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Creator Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Role:</span>
                        <span class="text-sm font-medium text-orange-600 ml-1">Content Creator</span>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Rating:</span>
                        <span class="text-sm font-medium text-yellow-600 ml-1">4.6 ⭐</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i data-lucide="file-text" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Published Content</p>
                        <p class="text-2xl font-bold text-gray-900">24</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i data-lucide="download" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Downloads</p>
                        <p class="text-2xl font-bold text-gray-900">1,247</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i data-lucide="dollar-sign" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-gray-900">$1,680</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i data-lucide="trending-up" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Growth Rate</p>
                        <p class="text-2xl font-bold text-gray-900">+23%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- My Content -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">My Content</h2>
                        <a href="#" class="text-orange-600 hover:text-orange-700 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                    <i data-lucide="play" class="h-8 w-8 text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-medium text-gray-900">Advanced Calculus Course</h3>
                                <p class="text-sm text-gray-600">Video Course • 12 lessons • $49.99</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <span class="text-sm text-green-600">156 downloads</span>
                                    <span class="text-sm text-blue-600">4.8 ⭐ (23 reviews)</span>
                                </div>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <button class="px-3 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">Edit</button>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Analytics</button>
                            </div>
                        </div>

                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                                    <i data-lucide="file-text" class="h-8 w-8 text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-medium text-gray-900">Physics Lab Manual</h3>
                                <p class="text-sm text-gray-600">PDF Guide • 45 pages • $19.99</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <span class="text-sm text-green-600">89 downloads</span>
                                    <span class="text-sm text-blue-600">4.6 ⭐ (12 reviews)</span>
                                </div>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <button class="px-3 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">Edit</button>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Analytics</button>
                            </div>
                        </div>

                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center">
                                    <i data-lucide="headphones" class="h-8 w-8 text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-medium text-gray-900">Chemistry Podcast Series</h3>
                                <p class="text-sm text-gray-600">Audio Content • 8 episodes • $29.99</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <span class="text-sm text-green-600">234 downloads</span>
                                    <span class="text-sm text-blue-600">4.9 ⭐ (45 reviews)</span>
                                </div>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <button class="px-3 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">Edit</button>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Analytics</button>
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
                                <i data-lucide="download" class="h-4 w-4 text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">"Advanced Calculus Course" purchased by John Smith</p>
                                <p class="text-xs text-gray-500">2 hours ago • $49.99</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i data-lucide="star" class="h-4 w-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">New 5-star review for "Physics Lab Manual"</p>
                                <p class="text-xs text-gray-500">5 hours ago • "Excellent resource!"</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i data-lucide="upload" class="h-4 w-4 text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Content "Statistics Cheat Sheet" approved and published</p>
                                <p class="text-xs text-gray-500">1 day ago</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <i data-lucide="dollar-sign" class="h-4 w-4 text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Payout of $340.50 processed</p>
                                <p class="text-xs text-gray-500">2 days ago</p>
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
                        <button class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-600 to-purple-600 text-white rounded-lg hover:from-orange-700 hover:to-purple-700 transition-all duration-200">
                            <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                            Upload Content
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="bar-chart" class="h-4 w-4 mr-2"></i>
                            View Analytics
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="settings" class="h-4 w-4 mr-2"></i>
                            Manage Pricing
                        </button>
                    </div>
                </div>

                <!-- Earnings Overview -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Earnings Overview</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">This Week</span>
                            <span class="text-sm font-medium text-gray-900">$340</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">This Month</span>
                            <span class="text-sm font-medium text-gray-900">$1,680</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Earnings</span>
                            <span class="text-sm font-medium text-gray-900">$12,450</span>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Available Balance</span>
                            <span class="text-lg font-bold text-green-600">$890</span>
                        </div>
                        <button class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Request Payout
                        </button>
                    </div>
                </div>

                <!-- Top Performing Content -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Top Performing</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Chemistry Podcast</h3>
                                <p class="text-xs text-gray-600">234 downloads</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">$698</p>
                                <p class="text-xs text-gray-500">Revenue</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Calculus Course</h3>
                                <p class="text-xs text-gray-600">156 downloads</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">$779</p>
                                <p class="text-xs text-gray-500">Revenue</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Physics Lab Manual</h3>
                                <p class="text-xs text-gray-600">89 downloads</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">$178</p>
                                <p class="text-xs text-gray-500">Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Status -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Content Status</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Published</span>
                            <span class="text-sm font-medium text-green-600">24</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Under Review</span>
                            <span class="text-sm font-medium text-yellow-600">3</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Draft</span>
                            <span class="text-sm font-medium text-gray-600">7</span>
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
