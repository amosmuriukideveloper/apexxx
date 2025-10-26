<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Course Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-8 text-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">
                            {{ ucfirst($record->difficulty) }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                            {{ $record->category->name }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $record->title }}</h1>
                    <p class="text-lg text-white/90 mb-4">{{ $record->short_description }}</p>
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-5 h-5" />
                            <span>{{ $record->creator->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-star class="w-5 h-5" />
                            <span>{{ $record->average_rating ? number_format($record->average_rating, 1) : 'No ratings' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-users class="w-5 h-5" />
                            <span>{{ number_format($record->total_enrollments) }} students</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="w-5 h-5" />
                            <span>{{ $record->total_duration_formatted }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-center">
                    @if($record->thumbnail)
                    <img src="{{ Storage::url($record->thumbnail) }}" alt="{{ $record->title }}" 
                         class="rounded-lg shadow-2xl w-full max-w-sm">
                    @endif
                </div>
            </div>
        </div>

        <!-- Course Info Tabs -->
        <div x-data="{ tab: 'overview' }" class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <!-- Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-4 px-6">
                    <button @click="tab = 'overview'" 
                            :class="tab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-3 py-4 border-b-2 font-medium text-sm">
                        Overview
                    </button>
                    <button @click="tab = 'curriculum'" 
                            :class="tab === 'curriculum' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-3 py-4 border-b-2 font-medium text-sm">
                        Curriculum
                    </button>
                    <button @click="tab = 'instructor'" 
                            :class="tab === 'instructor' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-3 py-4 border-b-2 font-medium text-sm">
                        Instructor
                    </button>
                    <button @click="tab = 'reviews'" 
                            :class="tab === 'reviews' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-3 py-4 border-b-2 font-medium text-sm">
                        Reviews
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div x-show="tab === 'overview'" class="space-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">About This Course</h3>
                        <div class="prose dark:prose-invert max-w-none">
                            {!! $record->description !!}
                        </div>
                    </div>

                    @if($record->objectives)
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">What You'll Learn</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($record->objectives as $objective)
                            <div class="flex items-start gap-2">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" />
                                <span class="text-gray-700 dark:text-gray-300">{{ $objective }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($record->requirements)
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Requirements</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                            @foreach($record->requirements as $requirement)
                            <li>{{ $requirement }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($record->target_audience)
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Who This Course Is For</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                            @foreach($record->target_audience as $audience)
                            <li>{{ $audience }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Curriculum Tab -->
                <div x-show="tab === 'curriculum'" class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        Course Content - {{ $record->sections->count() }} Sections • {{ $record->total_lectures }} Lectures
                    </h3>
                    
                    @foreach($record->sections as $section)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="bg-gray-50 dark:bg-gray-900 p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $section->title }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $section->lectures->count() }} lectures • {{ $section->duration_minutes ?? 0 }} min
                            </p>
                        </div>
                        <div class="p-4 space-y-2">
                            @foreach($section->lectures as $lecture)
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center gap-3">
                                    @if($lecture->type === 'video')
                                        <x-heroicon-o-play-circle class="w-5 h-5 text-blue-500" />
                                    @elseif($lecture->type === 'article')
                                        <x-heroicon-o-document-text class="w-5 h-5 text-green-500" />
                                    @elseif($lecture->type === 'quiz')
                                        <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-purple-500" />
                                    @endif
                                    <span class="text-gray-700 dark:text-gray-300">{{ $lecture->title }}</span>
                                    @if($lecture->is_preview)
                                        <span class="text-xs text-blue-600 font-semibold">FREE PREVIEW</span>
                                    @endif
                                </div>
                                @if($lecture->video_duration)
                                <span class="text-sm text-gray-500">{{ gmdate('i:s', $lecture->video_duration) }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Instructor Tab -->
                <div x-show="tab === 'instructor'" class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr($record->creator->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->creator->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">Course Instructor</p>
                            <div class="flex items-center gap-4 mt-3 text-sm text-gray-600 dark:text-gray-400">
                                <span>{{ $record->creator->createdCourses()->count() }} courses</span>
                                <span>{{ $record->creator->createdCourses()->sum('total_enrollments') }} students</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div x-show="tab === 'reviews'" class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Student Reviews</h3>
                    @forelse($record->approvedReviews as $review)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">{{ $review->user->name }}</span>
                                <span class="text-yellow-500">{{ str_repeat('⭐', $review->rating) }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300">{{ $review->review }}</p>
                    </div>
                    @empty
                    <p class="text-center py-8 text-gray-500">No reviews yet. Be the first to review!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
