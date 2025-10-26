<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content Area (Video/Content Player) -->
        <div class="lg:col-span-3 space-y-4">
            <!-- Video/Content Player -->
            <div class="bg-black rounded-lg overflow-hidden" style="aspect-ratio: 16/9;">
                @if($currentLecture)
                    @if($currentLecture->type === 'video' && $currentLecture->video_path)
                        <video id="videoPlayer" class="w-full h-full" controls>
                            <source src="{{ Storage::url($currentLecture->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif($currentLecture->type === 'article')
                        <div class="bg-white dark:bg-gray-800 p-8 overflow-y-auto h-full">
                            <div class="prose dark:prose-invert max-w-none">
                                {!! $currentLecture->content !!}
                            </div>
                        </div>
                    @elseif($currentLecture->type === 'quiz')
                        <div class="bg-white dark:bg-gray-800 flex items-center justify-center h-full">
                            <div class="text-center p-8">
                                <x-heroicon-o-clipboard-document-check class="w-16 h-16 mx-auto mb-4 text-purple-500" />
                                <h3 class="text-xl font-bold mb-2">Quiz Time!</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Test your knowledge</p>
                                <button class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                    Start Quiz
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex items-center justify-center h-full text-white">
                        <div class="text-center">
                            <x-heroicon-o-play class="w-20 h-20 mx-auto mb-4 opacity-50" />
                            <p>Select a lecture to begin</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Lecture Info & Actions -->
            @if($currentLecture)
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $currentLecture->title }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $currentLecture->description }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        @if(!in_array($currentLecture->id, $completedLectures))
                        <button wire:click="markComplete" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Mark as Complete
                        </button>
                        @else
                        <span class="px-4 py-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-lg flex items-center gap-2">
                            <x-heroicon-o-check-circle class="w-5 h-5" />
                            Completed
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Attachments -->
                @if($currentLecture->attachments && count($currentLecture->attachments) > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <h3 class="font-semibold mb-2">Resources</h3>
                    <div class="space-y-2">
                        @foreach($currentLecture->attachments as $attachment)
                        <a href="{{ Storage::url($attachment) }}" 
                           class="flex items-center gap-2 text-blue-600 hover:text-blue-700"
                           target="_blank">
                            <x-heroicon-o-document class="w-5 h-5" />
                            <span>{{ basename($attachment) }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Notes Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">My Notes</h3>
                <textarea 
                    class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                    rows="4"
                    placeholder="Take notes while learning..."></textarea>
                <button class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Notes
                </button>
            </div>
        </div>

        <!-- Sidebar (Course Curriculum) -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow sticky top-4">
                <!-- Course Progress Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Course Content</h3>
                    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span>Your Progress</span>
                        <span class="font-semibold">{{ $this->getCompletionPercentage() }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all" 
                             style="width: {{ $this->getCompletionPercentage() }}%"></div>
                    </div>
                </div>

                <!-- Curriculum List -->
                <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
                    @foreach($sections as $section)
                    <div x-data="{ expanded: true }" class="border-b border-gray-200 dark:border-gray-700">
                        <!-- Section Header -->
                        <button @click="expanded = !expanded" 
                                class="w-full p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-gray-900 dark:text-white text-sm">
                                    {{ $section->title }}
                                </h4>
                                <x-heroicon-o-chevron-down class="w-4 h-4 transition-transform" 
                                                          :class="expanded ? 'rotate-180' : ''" />
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $section->lectures->count() }} lectures
                            </p>
                        </button>

                        <!-- Section Lectures -->
                        <div x-show="expanded" class="bg-gray-50 dark:bg-gray-900">
                            @foreach($section->lectures as $lecture)
                            <button wire:click="selectLecture({{ $lecture->id }})"
                                    class="w-full p-3 text-left hover:bg-gray-100 dark:hover:bg-gray-800 transition border-l-4 {{ $currentLecture && $currentLecture->id === $lecture->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent' }}">
                                <div class="flex items-center gap-2">
                                    @if(in_array($lecture->id, $completedLectures))
                                        <x-heroicon-o-check-circle class="w-4 h-4 text-green-500 flex-shrink-0" />
                                    @elseif($lecture->type === 'video')
                                        <x-heroicon-o-play-circle class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                    @elseif($lecture->type === 'article')
                                        <x-heroicon-o-document-text class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                    @elseif($lecture->type === 'quiz')
                                        <x-heroicon-o-clipboard-document-check class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $lecture->title }}
                                        </p>
                                        @if($lecture->video_duration)
                                        <p class="text-xs text-gray-500">{{ gmdate('i:s', $lecture->video_duration) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-mark video as complete when finished
        document.getElementById('videoPlayer')?.addEventListener('ended', function() {
            @this.call('markComplete');
        });
    </script>
</x-filament-panels::page>
