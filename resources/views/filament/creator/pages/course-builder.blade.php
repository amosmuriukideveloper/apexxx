<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Course Progress Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sections</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->sections()->count() }}</p>
                    </div>
                    <x-heroicon-o-folder class="w-8 h-8 text-blue-500" />
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lectures</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->total_lectures }}</p>
                    </div>
                    <x-heroicon-o-play-circle class="w-8 h-8 text-green-500" />
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Duration</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->total_duration_formatted }}</p>
                    </div>
                    <x-heroicon-o-clock class="w-8 h-8 text-purple-500" />
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $record->status)) }}</p>
                    </div>
                    <x-heroicon-o-check-circle class="w-8 h-8 text-yellow-500" />
                </div>
            </div>
        </div>

        <!-- Course Builder Interface -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Course Curriculum</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Build your course structure with sections and lectures</p>
            </div>
            
            <div class="p-6">
                <div x-data="courseBuilder()" class="space-y-4">
                    <!-- Sections List -->
                    @foreach($record->sections()->orderBy('sort_order')->get() as $section)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        <!-- Section Header -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 flex items-center justify-between cursor-pointer"
                             x-on:click="toggleSection({{ $section->id }})">
                            <div class="flex items-center gap-3 flex-1">
                                <x-heroicon-o-bars-3 class="w-5 h-5 text-gray-400 cursor-move" />
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        Section {{ $section->sort_order }}: {{ $section->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $section->lectures->count() }} lectures • {{ $section->duration_minutes ?? 0 }} min
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    <x-heroicon-o-pencil class="w-5 h-5" />
                                </button>
                                <button type="button" class="text-red-600 hover:text-red-700 dark:text-red-400">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                                <x-heroicon-o-chevron-down class="w-5 h-5 text-gray-400" />
                            </div>
                        </div>
                        
                        <!-- Lectures List -->
                        <div class="p-4 space-y-2 bg-white dark:bg-gray-800">
                            @forelse($section->lectures()->orderBy('sort_order')->get() as $lecture)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                <div class="flex items-center gap-3">
                                    @if($lecture->type === 'video')
                                        <x-heroicon-o-play-circle class="w-5 h-5 text-blue-500" />
                                    @elseif($lecture->type === 'article')
                                        <x-heroicon-o-document-text class="w-5 h-5 text-green-500" />
                                    @elseif($lecture->type === 'quiz')
                                        <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-purple-500" />
                                    @else
                                        <x-heroicon-o-document class="w-5 h-5 text-gray-500" />
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $lecture->title }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ ucfirst($lecture->type) }}
                                            @if($lecture->video_duration)
                                                • {{ gmdate('i:s', $lecture->video_duration) }}
                                            @endif
                                            @if($lecture->is_preview)
                                                • <span class="text-blue-600">Free Preview</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="text-blue-600 hover:text-blue-700">
                                        <x-heroicon-o-pencil class="w-4 h-4" />
                                    </button>
                                    <button type="button" class="text-red-600 hover:text-red-700">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <x-heroicon-o-film class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>No lectures yet. Add your first lecture to this section.</p>
                            </div>
                            @endforelse
                            
                            <!-- Add Lecture Button -->
                            <button type="button" 
                                    class="w-full p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-400 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                <div class="flex items-center justify-center gap-2">
                                    <x-heroicon-o-plus class="w-5 h-5" />
                                    <span>Add Lecture</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Add Section Button -->
                    <button type="button" 
                            class="w-full p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-400 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        <div class="flex items-center justify-center gap-2">
                            <x-heroicon-o-plus-circle class="w-6 h-6" />
                            <span class="font-semibold">Add New Section</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center gap-2">
                <x-heroicon-o-light-bulb class="w-5 h-5" />
                Course Building Tips
            </h3>
            <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                <li class="flex items-start gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0 mt-0.5" />
                    <span>Organize content into logical sections with 3-7 lectures each</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0 mt-0.5" />
                    <span>Keep video lectures between 5-15 minutes for optimal engagement</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0 mt-0.5" />
                    <span>Add quizzes after major sections to reinforce learning</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0 mt-0.5" />
                    <span>Offer at least one free preview lecture to attract students</span>
                </li>
            </ul>
        </div>
    </div>

    <script>
        function courseBuilder() {
            return {
                expandedSections: [],
                toggleSection(sectionId) {
                    const index = this.expandedSections.indexOf(sectionId);
                    if (index > -1) {
                        this.expandedSections.splice(index, 1);
                    } else {
                        this.expandedSections.push(sectionId);
                    }
                }
            }
        }
    </script>
</x-filament-panels::page>
