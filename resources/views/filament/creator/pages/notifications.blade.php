<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header with Mark All Read -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $unreadCount }} unread notification{{ $unreadCount !== 1 ? 's' : '' }}
                </p>
            </div>
            @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Mark All as Read
            </button>
            @endif
        </div>

        <!-- Notifications Tabs -->
        <div x-data="{ tab: 'all' }" class="space-y-4">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-4">
                    <button @click="tab = 'all'" 
                            :class="tab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="px-3 py-2 border-b-2 font-medium text-sm">
                        All ({{ $notifications->count() }})
                    </button>
                    <button @click="tab = 'approvals'" 
                            :class="tab === 'approvals' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="px-3 py-2 border-b-2 font-medium text-sm">
                        Approvals ({{ count($grouped['approvals']) }})
                    </button>
                    <button @click="tab = 'rejections'" 
                            :class="tab === 'rejections' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="px-3 py-2 border-b-2 font-medium text-sm">
                        Rejections ({{ count($grouped['rejections']) }})
                    </button>
                    <button @click="tab = 'enrollments'" 
                            :class="tab === 'enrollments' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="px-3 py-2 border-b-2 font-medium text-sm">
                        Enrollments ({{ count($grouped['enrollments']) }})
                    </button>
                </nav>
            </div>

            <!-- All Notifications -->
            <div x-show="tab === 'all'" class="space-y-3">
                @forelse($notifications as $notification)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-blue-500' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                @php
                                    $type = $notification->data['type'] ?? 'info';
                                    $icon = match($type) {
                                        'course_approved' => 'heroicon-o-check-circle',
                                        'course_rejected' => 'heroicon-o-x-circle',
                                        'course_published' => 'heroicon-o-globe-alt',
                                        'new_enrollment' => 'heroicon-o-user-plus',
                                        'new_review' => 'heroicon-o-star',
                                        default => 'heroicon-o-bell',
                                    };
                                    $color = match($type) {
                                        'course_approved' => 'text-green-500',
                                        'course_rejected' => 'text-red-500',
                                        'course_published' => 'text-blue-500',
                                        'new_enrollment' => 'text-purple-500',
                                        'new_review' => 'text-yellow-500',
                                        default => 'text-gray-500',
                                    };
                                @endphp
                                <x-dynamic-component :component="$icon" class="w-5 h-5 {{ $color }}" />
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <div class="flex items-center gap-4 mt-3">
                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                @if(!$notification->read_at)
                                <button wire:click="markAsRead('{{ $notification->id }}')" 
                                        class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    Mark as read
                                </button>
                                @endif
                            </div>
                        </div>
                        @if(!$notification->read_at)
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-bell-slash class="w-16 h-16 mx-auto mb-4 opacity-50" />
                    <p>No notifications yet</p>
                </div>
                @endforelse
            </div>

            <!-- Approvals Tab -->
            <div x-show="tab === 'approvals'" class="space-y-3">
                @forelse($grouped['approvals'] as $notification)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-500 flex-shrink-0 mt-1" />
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $notification->data['title'] }}
                            </h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $notification->data['message'] }}
                            </p>
                            <span class="text-xs text-gray-500 mt-2 block">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center py-8 text-gray-500">No approval notifications</p>
                @endforelse
            </div>

            <!-- Rejections Tab -->
            <div x-show="tab === 'rejections'" class="space-y-3">
                @forelse($grouped['rejections'] as $notification)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-x-circle class="w-6 h-6 text-red-500 flex-shrink-0 mt-1" />
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $notification->data['title'] }}
                            </h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $notification->data['message'] }}
                            </p>
                            @if(isset($notification->data['reason']))
                            <div class="mt-2 p-3 bg-red-100 dark:bg-red-900/30 rounded">
                                <p class="text-sm text-red-800 dark:text-red-200 whitespace-pre-wrap">{{ $notification->data['reason'] }}</p>
                            </div>
                            @endif
                            <span class="text-xs text-gray-500 mt-2 block">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center py-8 text-gray-500">No rejection notifications</p>
                @endforelse
            </div>

            <!-- Enrollments Tab -->
            <div x-show="tab === 'enrollments'" class="space-y-3">
                @forelse($grouped['enrollments'] as $notification)
                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-user-plus class="w-6 h-6 text-purple-500 flex-shrink-0 mt-1" />
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $notification->data['title'] }}
                            </h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $notification->data['message'] }}
                            </p>
                            <span class="text-xs text-gray-500 mt-2 block">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center py-8 text-gray-500">No enrollment notifications</p>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
