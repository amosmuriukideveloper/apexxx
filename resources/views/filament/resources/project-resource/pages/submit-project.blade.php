<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        
        <x-slot name="footerActions">
            <x-filament::button
                type="button"
                color="gray"
                tag="a"
                :href="static::getResource()::getUrl('view', ['record' => $this->record])"
            >
                Cancel
            </x-filament::button>
        </x-slot>
    </form>
</x-filament-panels::page>
