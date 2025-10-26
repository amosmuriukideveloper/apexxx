<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        
        <div class="mt-6 flex justify-end gap-3">
            <x-filament::button
                type="button"
                color="gray"
                tag="a"
                :href="route('filament.tutor.resources.my-tutoring-sessions.index')"
            >
                Cancel
            </x-filament::button>
            
            <x-filament::button
                type="submit"
            >
                Complete Session
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
