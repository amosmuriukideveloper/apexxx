<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Enrolled Courses
        </x-slot>

        <div class="space-y-4">
            @forelse($this->getCourses() as $course)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <h4 class="font-semibold">{{ $course->title }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $course->instructor }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-medium">{{ $course->progress }}%</span>
                        <div class="w-24 h-2 bg-gray-200 rounded-full mt-1">
                            <div class="h-full bg-primary-600 rounded-full" style="width: {{ $course->progress }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No courses enrolled</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by enrolling in a course.</p>
                    <div class="mt-6">
                        <a href="{{ url('/student/courses') }}" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            Browse Courses
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
