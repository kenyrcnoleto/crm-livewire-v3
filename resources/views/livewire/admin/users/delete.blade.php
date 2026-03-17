    {{-- Do your work, then step back. --}}

    <x-modal
        wire:model="modal"
        title="Deletion Confirmation"
        subtitle="You are deleting the user {{ $user?->name }}"
        separator>

        @error('confirmation')
            <x-alert icon="o-exclamation-triangle" class="alert-danger"  >
            {{ $message }}
            </x-alert>
        @enderror

        <x-input
            class="input-sm"
            label="Write 'dart vader' to confirm the deletion"
             wire:model="confirmation_confirmation"
        />


        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
            <x-button label="Confirm" class="btn-primary" wire:click="destroy" />
        </x-slot:actions>
</x-modal>
