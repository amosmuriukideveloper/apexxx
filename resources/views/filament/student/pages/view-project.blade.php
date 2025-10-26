<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Project Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->title }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Project #{{ $record->project_number }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($record->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($record->status === 'assigned') bg-blue-100 text-blue-800
                        @elseif($record->status === 'in_progress') bg-purple-100 text-purple-800
                        @elseif($record->status === 'review') bg-indigo-100 text-indigo-800
                        @elseif($record->status === 'completed') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $record->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Project Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Project Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Subject</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $record->subject ?? $record->subject_area }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Project Type</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ ucwords(str_replace('_', ' ', $record->project_type)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Difficulty Level</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ ucfirst($record->difficulty_level) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Word Count</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ number_format($record->word_count) }} words</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Page Count</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $record->page_count }} pages</dd>
                    </div>
                </dl>
            </div>

            <!-- Timeline & Assignment -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Timeline & Assignment</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Deadline</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $record->deadline->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Submitted On</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $record->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @if($record->assigned_expert_id)
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Assigned Expert</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $record->assignedExpert->name ?? 'N/A' }}</dd>
                    </div>
                    @else
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Assignment Status</dt>
                        <dd class="text-base font-medium text-yellow-600">Awaiting Expert Assignment</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Budget</dt>
                        <dd class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($record->budget, 2) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Project Description</h3>
            <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $record->description }}</div>
        </div>

        <!-- Special Instructions -->
        @if($record->admin_notes)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Special Instructions</h3>
            <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $record->admin_notes }}</div>
        </div>
        @endif

        <!-- Attachments -->
        @if($record->attachments)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Reference Materials</h3>
            <div class="space-y-2">
                @php
                    $files = is_string($record->attachments) ? json_decode($record->attachments, true) : $record->attachments;
                @endphp
                @if($files && is_array($files))
                    @foreach($files as $file)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <a href="{{ Storage::url($file) }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ basename($file) }}
                        </a>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">No attachments</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Deliverables (if project completed) -->
        @if($record->deliverables && in_array($record->status, ['review', 'completed']))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Deliverables</h3>
            <div class="space-y-2">
                @php
                    $deliverables = is_string($record->deliverables) ? json_decode($record->deliverables, true) : $record->deliverables;
                @endphp
                @if($deliverables && is_array($deliverables))
                    @foreach($deliverables as $file)
                    <div class="flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900/20 rounded">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <a href="{{ Storage::url($file) }}" target="_blank" class="text-blue-600 hover:underline font-medium">
                            {{ basename($file) }}
                        </a>
                        <a href="{{ Storage::url($file) }}" download class="ml-auto">
                            <button class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                Download
                            </button>
                        </a>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">No deliverables yet</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Admin Notes (if any) -->
        @if($record->revision_notes)
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-amber-900 dark:text-amber-100 mb-4">Revision Notes</h3>
            <div class="text-amber-800 dark:text-amber-200 whitespace-pre-wrap">{{ $record->revision_notes }}</div>
        </div>
        @endif
    </div>
</x-filament-panels::page>
