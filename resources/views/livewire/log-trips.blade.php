<div>
    <form wire:submit="create">
        {{ $this->form }}

        <x-filament::button color="info" class="mt-5" type="submit">
            Submit
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
