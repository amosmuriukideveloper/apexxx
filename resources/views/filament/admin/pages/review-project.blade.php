<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Project Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Project Details</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Project Number</p>
                    <p class="font-semibold">{{ $record->project_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Student</p>
                    <p class="font-semibold">{{ $record->student->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Expert</p>
                    <p class="font-semibold">{{ $record->expert->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Subject</p>
                    <p class="font-semibold">{{ $record->subject->name }}</p>
                </div>
            </div>
        </div>

        <!-- Latest Submission -->
        @php
            $latestSubmission = $record->submissions()->latest()->first();
        @endphp
        
        @if($latestSubmission)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                Submission #{{ $latestSubmission->version }}
            </h2>
            
            <div class="space-y-4">
                <!-- Submission Notes -->
                @if($latestSubmission->submission_notes)
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Expert Notes</h3>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $latestSubmission->submission_notes }}</p>
                </div>
                @endif

                <!-- Quality Scores -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Turnitin Score</h3>
                        @if($latestSubmission->turnitin_score !== null)
                        <div class="flex items-center gap-2">
                            <div class="text-3xl font-bold {{ $latestSubmission->turnitin_score <= 15 ? 'text-green-600' : ($latestSubmission->turnitin_score <= 25 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $latestSubmission->turnitin_score }}%
                            </div>
                            @if($latestSubmission->turnitin_report)
                            <a href="{{ Storage::url($latestSubmission->turnitin_report) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-700 text-sm">
                                View Report
                            </a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            @if($latestSubmission->turnitin_score <= 15)
                                ✓ Acceptable
                            @elseif($latestSubmission->turnitin_score <= 25)
                                ⚠ Review Needed
                            @else
                                ✗ Too High
                            @endif
                        </p>
                        @else
                        <p class="text-gray-500">Not provided</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">AI Detection Score</h3>
                        @if($latestSubmission->ai_score !== null)
                        <div class="flex items-center gap-2">
                            <div class="text-3xl font-bold {{ $latestSubmission->ai_score <= 20 ? 'text-green-600' : ($latestSubmission->ai_score <= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $latestSubmission->ai_score }}%
                            </div>
                            @if($latestSubmission->ai_report)
                            <a href="{{ Storage::url($latestSubmission->ai_report) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-700 text-sm">
                                View Report
                            </a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            @if($latestSubmission->ai_score <= 20)
                                ✓ Acceptable
                            @elseif($latestSubmission->ai_score <= 40)
                                ⚠ Review Needed
                            @else
                                ✗ Too High
                            @endif
                        </p>
                        @else
                        <p class="text-gray-500">Not provided</p>
                        @endif
                    </div>
                </div>

                <!-- Deliverable Files -->
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Deliverable Files</h3>
                    <div class="space-y-2">
                        @foreach($latestSubmission->files as $file)
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

                <!-- Review Checklist -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Quality Checklist</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded">
                            <span class="text-gray-700 dark:text-gray-300">All requirements met</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded">
                            <span class="text-gray-700 dark:text-gray-300">Turnitin score acceptable (≤15%)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded">
                            <span class="text-gray-700 dark:text-gray-300">AI detection score acceptable (≤20%)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded">
                            <span class="text-gray-700 dark:text-gray-300">Formatting correct</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded">
                            <span class="text-gray-700 dark:text-gray-300">Citations proper</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded">
                            <span class="text-gray-700 dark:text-gray-300">Grammar and spelling correct</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous Revisions -->
        @if($record->revisions()->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Revision History</h2>
            <div class="space-y-3">
                @foreach($record->revisions as $revision)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <span class="font-semibold">Revision #{{ $loop->iteration }}</span>
                            <span class="text-sm text-gray-500 ml-2">{{ $revision->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="px-2 py-1 bg-{{ $revision->status === 'completed' ? 'green' : 'yellow' }}-100 text-{{ $revision->status === 'completed' ? 'green' : 'yellow' }}-800 rounded text-xs">
                            {{ ucfirst($revision->status) }}
                        </span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $revision->revision_notes }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @else
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
            <p class="text-yellow-800 dark:text-yellow-200">No submission available yet.</p>
        </div>
        @endif
    </div>
</x-filament-panels::page>
