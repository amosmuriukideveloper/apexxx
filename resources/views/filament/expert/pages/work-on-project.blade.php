<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Project Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ $record->title }}</h1>
                    <div class="flex items-center gap-4 text-sm">
                        <span>Project #{{ $record->project_number }}</span>
                        <span>•</span>
                        <span>{{ $record->subject->name }}</span>
                        <span>•</span>
                        <span>{{ $record->word_count ? number_format($record->word_count) . ' words' : $record->page_count . ' pages' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-90">Due</div>
                    <div class="text-xl font-bold">{{ $record->deadline->format('M d, H:i') }}</div>
                    <div class="text-sm">{{ $record->deadline->diffForHumans() }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Project Requirements -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Project Requirements</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        <p>{{ $record->description }}</p>
                        
                        @if($record->special_instructions)
                        <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded">
                            <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Special Instructions</h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">{{ $record->special_instructions }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Reference Files -->
                    @if($record->reference_files)
                    <div class="mt-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Reference Materials</h3>
                        <div class="space-y-2">
                            @foreach($record->reference_files as $file)
                            <a href="{{ Storage::url($file) }}" 
                               download
                               class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                <x-heroicon-o-document class="w-5 h-5 text-blue-600" />
                                <span class="text-gray-900 dark:text-white">{{ basename($file) }}</span>
                                <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-gray-400 ml-auto" />
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Progress Tracking -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Progress Notes</h2>
                    
                    <!-- Add New Note -->
                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Add Progress Update
                        </label>
                        <textarea wire:model="newNote" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                  placeholder="Describe what you've completed..."></textarea>
                        
                        <div class="flex items-center gap-4 mt-3">
                            <div class="flex-1">
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Progress %</label>
                                <input wire:model="progressPercentage" 
                                       type="number" 
                                       min="0" 
                                       max="100"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800">
                            </div>
                            <button wire:click="addProgressNote" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mt-6">
                                Save Note
                            </button>
                        </div>
                    </div>

                    <!-- Progress History -->
                    <div class="space-y-2">
                        @foreach($record->progressNotes()->where('expert_id', Auth::id())->latest()->get() as $note)
                        <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $note->created_at->format('M d, H:i') }}</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded text-xs">
                                    {{ $note->progress_percentage }}%
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $note->note }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Time Tracking -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Time Tracking</h2>
                    
                    @if($activeTimeLog)
                    <!-- Active Timer -->
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-green-800 dark:text-green-200 font-semibold">Timer Running</span>
                            <span class="text-2xl font-mono text-green-600">
                                {{ $activeTimeLog->started_at->diffInMinutes(now()) }} min
                            </span>
                        </div>
                        <textarea wire:model="activityDescription" 
                                  rows="2" 
                                  class="w-full px-3 py-2 border border-green-300 rounded-lg mb-2"
                                  placeholder="What are you working on?"></textarea>
                        <button wire:click="stopTimer" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Stop Timer
                        </button>
                    </div>
                    @else
                    <button wire:click="startTimer" 
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 mb-4">
                        Start Timer
                    </button>
                    @endif

                    <!-- Time Log History -->
                    <div class="space-y-2">
                        @foreach($record->timeLogs()->where('expert_id', Auth::id())->whereNotNull('ended_at')->latest()->limit(5)->get() as $log)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg text-sm">
                            <div>
                                <div class="font-semibold">{{ $log->started_at->format('M d, H:i') }}</div>
                                <div class="text-gray-600 dark:text-gray-400">{{ $log->activity_description }}</div>
                            </div>
                            <div class="text-blue-600 font-semibold">{{ $log->duration_minutes }} min</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Project Stats</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Earnings</div>
                            <div class="text-2xl font-bold text-green-600">${{ number_format($record->expert_earnings, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Time Spent</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ $record->timeLogs()->where('expert_id', Auth::id())->sum('duration_minutes') }} min
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Progress</div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $progressPercentage }}%</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ MyProjectResource::getUrl('submit', ['record' => $record]) }}" 
                           class="block w-full px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition">
                            Submit Work
                        </a>
                        <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                            Message Student
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
