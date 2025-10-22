<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Submission Information -->
        <div>
            {{ $this->infolist }}
        </div>

        <!-- Review Form -->
        <form wire:submit="submit">
            {{ $this->form }}
            
            <div class="mt-6 flex justify-end gap-3">
                <x-filament::button 
                    type="button" 
                    color="gray"
                    tag="a"
                    :href="static::getResource()::getUrl('view', ['record' => $this->record])"
                >
                    Cancel
                </x-filament::button>
                
                <x-filament::button type="submit" color="primary">
                    Submit Review Decision
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
