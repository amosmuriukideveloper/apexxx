@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-purple-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Role:</span>
                        <span class="text-sm font-medium text-red-600 ml-1">Super Admin</span>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-500">Access Level:</span>
                        <span class="text-sm font-medium text-purple-600 ml-1">Full Control</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Critical Alerts -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <i data-lucide="shield-alert" class="h-8 w-8 mr-4"></i>
                    <div>
                        <h2 class="text-xl font-bold">System Security Status</h2>
                        <p class="text-red-100">All systems operational • Last security scan: 2 hours ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i data-lucide="globe" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Platform Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">$127K</p>
                        <p class="text-xs text-green-600">+15% this month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i data-lucide="users" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">3,247</p>
                        <p class="text-xs text-blue-600">+234 this week</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i data-lucide="activity" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">System Load</p>
                        <p class="text-2xl font-bold text-gray-900">67%</p>
                        <p class="text-xs text-green-600">Optimal</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i data-lucide="database" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Data Storage</p>
                        <p class="text-2xl font-bold text-gray-900">2.4TB</p>
                        <p class="text-xs text-yellow-600">78% capacity</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i data-lucide="shield" class="h-6 w-6 text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Security Score</p>
                        <p class="text-2xl font-bold text-gray-900">98%</p>
                        <p class="text-xs text-green-600">Excellent</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Admin Management -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Admin Management</h2>
                        <button class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                            <i data-lucide="user-plus" class="h-4 w-4 mr-2 inline"></i>
                            Add Admin
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i data-lucide="user" class="h-5 w-5 text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-medium text-gray-900">Sarah Johnson</h3>
                                    <p class="text-sm text-gray-600">sarah.johnson@apexscholars.com</p>
                                    <p class="text-xs text-gray-500">Last active: 2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200">Edit</button>
                                <button class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded-full hover:bg-red-200">Suspend</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i data-lucide="user" class="h-5 w-5 text-purple-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-medium text-gray-900">Michael Chen</h3>
                                    <p class="text-sm text-gray-600">michael.chen@apexscholars.com</p>
                                    <p class="text-xs text-gray-500">Last active: 1 day ago</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                                <button class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200">Edit</button>
                                <button class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded-full hover:bg-red-200">Suspend</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Configuration -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">System Configuration</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-lg">
                                    <i data-lucide="settings" class="h-6 w-6 text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">Platform Settings</h3>
                                    <p class="text-sm text-gray-600">Configure global platform settings</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-lg">
                                    <i data-lucide="credit-card" class="h-6 w-6 text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">Payment Config</h3>
                                    <p class="text-sm text-gray-600">Manage payment gateways</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-100 rounded-lg">
                                    <i data-lucide="mail" class="h-6 w-6 text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">Email Templates</h3>
                                    <p class="text-sm text-gray-600">Customize email notifications</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex items-center">
                                <div class="p-3 bg-red-100 rounded-lg">
                                    <i data-lucide="shield" class="h-6 w-6 text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">Security Settings</h3>
                                    <p class="text-sm text-gray-600">Configure security policies</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Logs -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Recent System Logs</h2>
                        <a href="#" class="text-red-600 hover:text-red-700 text-sm font-medium">View All Logs</a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                            <div class="p-1 bg-green-100 rounded">
                                <i data-lucide="check" class="h-3 w-3 text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">System backup completed successfully</p>
                                <p class="text-xs text-gray-500">2024-12-12 03:00:00 UTC • Size: 2.4GB</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                            <div class="p-1 bg-blue-100 rounded">
                                <i data-lucide="info" class="h-3 w-3 text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">Database optimization completed</p>
                                <p class="text-xs text-gray-500">2024-12-12 02:30:00 UTC • Performance improved by 12%</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                            <div class="p-1 bg-yellow-100 rounded">
                                <i data-lucide="alert-triangle" class="h-3 w-3 text-yellow-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">High CPU usage detected on server-02</p>
                                <p class="text-xs text-gray-500">2024-12-12 01:45:00 UTC • Peak: 89% • Auto-scaled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Super Admin Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Super Admin Actions</h2>
                    <div class="space-y-3">
                        <button class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-600 to-purple-600 text-white rounded-lg hover:from-red-700 hover:to-purple-700 transition-all duration-200">
                            <i data-lucide="database" class="h-4 w-4 mr-2"></i>
                            Database Management
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="server" class="h-4 w-4 mr-2"></i>
                            Server Configuration
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="shield" class="h-4 w-4 mr-2"></i>
                            Security Audit
                        </button>
                        
                        <button class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                            System Backup
                        </button>
                    </div>
                </div>

                <!-- Platform Analytics -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Platform Analytics</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Daily Active Users</span>
                            <span class="text-sm font-medium text-gray-900">1,247</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Conversion Rate</span>
                            <span class="text-sm font-medium text-green-600">12.4%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Avg. Session Duration</span>
                            <span class="text-sm font-medium text-gray-900">24m 32s</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Bounce Rate</span>
                            <span class="text-sm font-medium text-yellow-600">23.1%</span>
                        </div>
                    </div>
                </div>

                <!-- Critical Alerts -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Critical Alerts</h2>
                    <div class="space-y-3">
                        <div class="p-3 bg-red-50 rounded-lg border-l-4 border-red-400">
                            <h3 class="text-sm font-medium text-red-800">Security Alert</h3>
                            <p class="text-xs text-red-600">Multiple failed login attempts detected</p>
                            <p class="text-xs text-gray-500">12 minutes ago</p>
                        </div>

                        <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                            <h3 class="text-sm font-medium text-yellow-800">Performance Warning</h3>
                            <p class="text-xs text-yellow-600">Database query time increased by 15%</p>
                            <p class="text-xs text-gray-500">1 hour ago</p>
                        </div>

                        <div class="p-3 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                            <h3 class="text-sm font-medium text-blue-800">System Update</h3>
                            <p class="text-xs text-blue-600">Security patch available for installation</p>
                            <p class="text-xs text-gray-500">3 hours ago</p>
                        </div>
                    </div>
                </div>

                <!-- Resource Usage -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Resource Usage</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">CPU Usage</span>
                                <span class="text-gray-900">67%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 67%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Memory Usage</span>
                                <span class="text-gray-900">78%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Disk Usage</span>
                                <span class="text-gray-900">45%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Network I/O</span>
                                <span class="text-gray-900">23%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-600 h-2 rounded-full" style="width: 23%"></div>
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
