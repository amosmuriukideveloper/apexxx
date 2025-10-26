<x-filament-widgets::widget>
    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Featured Courses</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($this->getFeaturedCourses() as $course)
            <a href="{{ route('filament.student.resources.courses.view', ['record' => $course]) }}" 
               class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition overflow-hidden group">
                <!-- Thumbnail -->
                <div class="relative overflow-hidden" style="aspect-ratio: 16/9;">
                    @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" 
                         alt="{{ $course->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                        <x-heroicon-o-academic-cap class="w-20 h-20 text-white opacity-50" />
                    </div>
                    @endif
                    
                    <!-- Price Badge -->
                    <div class="absolute top-3 right-3">
                        @if($course->price > 0)
                        <span class="px-3 py-1 bg-white dark:bg-gray-900 rounded-full text-sm font-bold">
                            ${{ number_format($course->current_price, 2) }}
                        </span>
                        @else
                        <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-bold">
                            FREE
                        </span>
                        @endif
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded">
                            {{ ucfirst($course->difficulty) }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $course->category->name }}</span>
                    </div>
                    
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-blue-600 transition">
                        {{ $course->title }}
                    </h3>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                        {{ $course->short_description }}
                    </p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-1">
                            <x-heroicon-o-user class="w-4 h-4" />
                            <span>{{ $course->creator->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <x-heroicon-o-star class="w-4 h-4 text-yellow-500" />
                            <span>{{ $course->average_rating ? number_format($course->average_rating, 1) : 'New' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <x-heroicon-o-users class="w-4 h-4" />
                            {{ number_format($course->total_enrollments) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <x-heroicon-o-clock class="w-4 h-4" />
                            {{ $course->total_duration_formatted }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
