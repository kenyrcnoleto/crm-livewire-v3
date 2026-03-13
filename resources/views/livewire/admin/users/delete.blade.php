<div>
    {{-- Do your work, then step back. --}}
    <x-button icon="o-trash" @click="$wire.modal = true" spinner class="btn-sm" />

    <x-modal
        wire:model="modal"
        title="Deletion Confirmation"
        subtitle="You are deleting the user {{ $user->name }}"
        separator>

        @error('confirmation')
            <x-alert icon="o-exclamation-triangle" class="alert-danger"  >
            {{ $message }}
            </x-alert>
        @enderror

        <x-input
            class="input-sm"
            label="Write 'KENOBI' to confirm the deletion"
             wire:model="confirmation_confirmation"
        />

         {{-- We can use the `actions` slot from `x-modal` to place the buttons in the right place, but we can also place them outside of it, as long as we use the `actions` slot from `x-form` inside the modal. --}}
         {{-- <x-slot:actions></x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />

        {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
            <x-button label="Confirm" class="btn-primary" wire:click="destroy" />
        </x-slot:actions>
</x-modal>
</div>
