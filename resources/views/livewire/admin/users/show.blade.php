<div>
    {{-- The Master doesn't talk, he acts. --}}


    <x-modal wire:model="modal" :title="$user?->name" class="backdrop-blur">
        @if ($user)
            <div class="space-y-2">

                <x-input readonly label="Name" value:model="$user->name" />
                <x-input readonly label="Email" value:model="$user->email" />
                <x-input readonly label="Created At" :value="$user->created_at->format('d/m/Y H:i')" />
                <x-input readonly label="Updated At" :value="$user->updated_at->format('d/m/Y H:i')" />
                <x-input readonly label="Deleted At" :value="$user->deleted_at?->format('d/m/Y H:i') ?? 'N/A'" />
                <x-input readonly label="Deleted By" :value="$user->deletedBy?->name ?? 'N/A'" />
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
        </x-slot:actions>
    </x-modal>

    <x-button label="Open" @click="$wire.modal = true" />
</div>
