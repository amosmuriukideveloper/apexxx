<x-filament-panels::page>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Project Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Submit: {{ $record->title }}</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Project #</span>
                    <span class="font-semibold ml-2">{{ $record->project_number }}</span>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Deadline</span>
                    <span class="font-semibold ml-2">{{ $record->deadline->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Submission Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Upload Deliverables</h3>
            
            <!-- Deliverable Files -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Deliverable Files *
                </label>
                <input type="file" 
                       wire:model="deliverableFiles" 
                       multiple
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Upload all completed work files</p>
            </div>

            <!-- Turnitin Report -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Turnitin Report * (PDF)
                </label>
                <input type="file" 
                       wire:model="turnitinReport" 
                       accept=".pdf"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                <div class="mt-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Turnitin Score (%)</label>
                    <input type="number" 
                           wire:model="turnitinScore" 
                           min="0" 
                           max="100" 
                           step="0.1"
                           class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                           placeholder="15.5">
                </div>
            </div>

            <!-- AI Detection Report -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    AI Detection Report * (PDF)
                </label>
                <input type="file" 
                       wire:model="aiReport" 
                       accept=".pdf"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                <div class="mt-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">AI Detection Score (%)</label>
                    <input type="number" 
                           wire:model="aiScore" 
                           min="0" 
                           max="100" 
                           step="0.1"
                           class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                           placeholder="12.3">
                </div>
            </div>

            <!-- Submission Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Submission Notes *
                </label>
                <textarea wire:model="submissionNotes" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                          placeholder="Explain your approach, any challenges faced, and key points about the work..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Minimum 20 characters</p>
            </div>

            <!-- Pre-submission Checklist -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Pre-Submission Checklist</h4>
                <div class="space-y-2">
                    <label class="flex items-start gap-2">
                        <input type="checkbox" wire:model="checklist.requirements_met" class="mt-1 rounded">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            All project requirements have been met as specified
                        </span>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="checkbox" wire:model="checklist.formatting_correct" class="mt-1 rounded">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Formatting is correct (citations, page numbers, fonts, etc.)
                        </span>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="checkbox" wire:model="checklist.citations_proper" class="mt-1 rounded">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            All sources are properly cited
                        </span>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="checkbox" wire:model="checklist.grammar_checked" class="mt-1 rounded">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Grammar and spelling have been thoroughly checked
                        </span>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="checkbox" wire:model="checklist.turnitin_uploaded" class="mt-1 rounded">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            Turnitin report uploaded (score ≤15% recommended)
                        </span>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="checkbox" wire:model="checklist.ai_detection_uploaded" class="mt-1 rounded">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            AI detection report uploaded (score ≤20% recommended)
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Warning -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" />
                <div class="text-sm text-yellow-800 dark:text-yellow-200">
                    <p class="font-semibold mb-1">Important Reminders:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Ensure Turnitin score is below 15% for best results</li>
                        <li>AI detection score should be below 20%</li>
                        <li>All files must be properly named and organized</li>
                        <li>Double-check that you've met all requirements</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4">
            <button wire:click="submit" 
                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                Submit for Review
            </button>
            <a href="{{ MyProjectResource::getUrl('work', ['record' => $record]) }}" 
               class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Back to Work
            </a>
        </div>
    </div>
</x-filament-panels::page>
