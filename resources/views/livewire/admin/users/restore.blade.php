<x-modal
        wire:model="modal"
        title="Restore Confirmation"
        subtitle="You are restoring the user {{ $user?->name }}"
        separator>

        @error('confirmation')
            <x-alert icon="o-exclamation-triangle" class="alert-danger"  >
            {{ $message }}
            </x-alert>
        @enderror

        <x-input
            class="input-sm"
            label="Write 'kenobi' to confirm the restoration"
             wire:model="confirmation_confirmation"
        />


        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
            <x-button label="Confirm" class="btn-primary" wire:click="restore" />
        </x-slot:actions>
</x-modal>
