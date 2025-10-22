<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Application Details -->
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
                    :href="static::getResource()::getUrl('index')"
                >
                    Cancel
                </x-filament::button>
                
                <x-filament::button type="submit" color="primary">
                    Submit Review
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
