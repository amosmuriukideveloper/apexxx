<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <!-- Course Header -->
            <div class="flex items-start gap-6">
                @if($course->thumbnail)
                <div class="flex-shrink-0">
                    <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-48 h-32 object-cover rounded-lg">
                </div>
                @endif
                
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h2>
                    
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ ucfirst($course->difficulty) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            ${{ number_format($course->price, 2) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                            {{ $course->language }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 dark:text-gray-400">{{ $course->short_description ?? $course->description }}</p>
                </div>
            </div>

            <!-- Course Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Sections</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSections }}</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Lectures</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalLectures }}</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Duration</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $formattedDuration }}</div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Quizzes</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $hasQuizzes ? 'Yes' : 'No' }}</div>
                </div>
            </div>

            <!-- Creator Information -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Creator Information</h3>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Name</div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $course->creator->name }}</div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Email</div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $course->creator->email }}</div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Submitted</div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $course->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- Course Content Preview -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Course Content</h3>
                <div class="space-y-3">
                    @foreach($course->sections as $section)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $section->title }}</h4>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $section->lectures->count() }} lectures</span>
                        </div>
                        @if($section->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $section->description }}</p>
                        @endif
                        <div class="space-y-1 ml-4">
                            @foreach($section->lectures as $lecture)
                            <div class="flex items-center gap-2 text-sm">
                                @if($lecture->type === 'video')
                                    <x-heroicon-o-play-circle class="w-4 h-4 text-blue-500" />
                                @elseif($lecture->type === 'article')
                                    <x-heroicon-o-document-text class="w-4 h-4 text-green-500" />
                                @elseif($lecture->type === 'quiz')
                                    <x-heroicon-o-clipboard-document-check class="w-4 h-4 text-purple-500" />
                                @else
                                    <x-heroicon-o-document class="w-4 h-4 text-gray-500" />
                                @endif
                                <span class="text-gray-700 dark:text-gray-300">{{ $lecture->title }}</span>
                                @if($lecture->video_duration)
                                <span class="text-gray-500 dark:text-gray-400 text-xs">{{ gmdate('i:s', $lecture->video_duration) }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($course->rejection_reason)
            <!-- Previous Rejection/Edit Notes -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-3">Previous Feedback</h3>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $course->rejection_reason }}</p>
                </div>
            </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
