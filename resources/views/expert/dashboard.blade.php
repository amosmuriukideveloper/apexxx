@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Expert Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Role:</span>
                        <span class="text-sm font-medium text-purple-600 ml-1">Expert</span>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Rating:</span>
                        <span class="text-sm font-medium text-yellow-600 ml-1">4.9 ⭐</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i data-lucide="briefcase" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Projects</p>
                        <p class="text-2xl font-bold text-gray-900">5</p>
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
                        <p class="text-2xl font-bold text-gray-900">47</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i data-lucide="dollar-sign" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-gray-900">$2,340</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i data-lucide="star" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Success Rate</p>
                        <p class="text-2xl font-bold text-gray-900">98%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Available Projects -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Available Projects</h2>
                        <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">Research Paper: Artificial Intelligence Ethics</h3>
                                    <p class="text-sm text-gray-600 mt-1">Computer Science • 3000 words • Due: Dec 18, 2024</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-sm text-green-600 font-medium">$180</span>
                                        <span class="text-xs text-gray-500">Posted 2 hours ago</span>
                                    </div>
                                </div>
                                <button class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">Business Case Study Analysis</h3>
                                    <p class="text-sm text-gray-600 mt-1">Business Studies • 2500 words • Due: Dec 22, 2024</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-sm text-green-600 font-medium">$150</span>
                                        <span class="text-xs text-gray-500">Posted 5 hours ago</span>
                                    </div>
                                </div>
                                <button class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Projects -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Current Projects</h2>
                        <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Marketing Strategy Report</h3>
                                    <p class="text-sm text-gray-600 mt-1">Student: John Smith • Due: Dec 16, 2024</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full" style="width: 80%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">80%</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Message</button>
                                    <button class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">Upload</button>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900">Psychology Research Paper</h3>
                                    <p class="text-sm text-gray-600 mt-1">Student: Sarah Wilson • Due: Dec 20, 2024</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full" style="width: 45%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">45%</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Message</button>
                                    <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Draft</button>
                                </div>
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
                        <button class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200">
                            <i data-lucide="search" class="h-4 w-4 mr-2"></i>
                            Browse Projects
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="upload" class="h-4 w-4 mr-2"></i>
                            Upload Deliverable
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="message-circle" class="h-4 w-4 mr-2"></i>
                            Messages
                        </button>
                    </div>
                </div>

                <!-- Earnings Overview -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Earnings Overview</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">This Week</span>
                            <span class="text-sm font-medium text-gray-900">$580</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">This Month</span>
                            <span class="text-sm font-medium text-gray-900">$2,340</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Earnings</span>
                            <span class="text-sm font-medium text-gray-900">$18,750</span>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Available Balance</span>
                            <span class="text-lg font-bold text-green-600">$1,240</span>
                        </div>
                        <button class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Withdraw Funds
                        </button>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Reviews</h2>
                    <div class="space-y-4">
                        <div class="border-l-4 border-yellow-400 pl-4">
                            <div class="flex items-center mb-1">
                                <span class="text-sm font-medium text-gray-900">Emily Chen</span>
                                <div class="ml-2 flex text-yellow-400">
                                    ⭐⭐⭐⭐⭐
                                </div>
                            </div>
                            <p class="text-xs text-gray-600">"Excellent work on the research paper. Very professional and delivered on time."</p>
                        </div>

                        <div class="border-l-4 border-yellow-400 pl-4">
                            <div class="flex items-center mb-1">
                                <span class="text-sm font-medium text-gray-900">Michael Brown</span>
                                <div class="ml-2 flex text-yellow-400">
                                    ⭐⭐⭐⭐⭐
                                </div>
                            </div>
                            <p class="text-xs text-gray-600">"Outstanding quality and attention to detail. Highly recommended!"</p>
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
