<div>
    {{-- The Master doesn't talk, he acts. --}}

    <x-drawer wire:model="modal" :title="$user?->name" class="w-11/12 p-4 backdrop-blur" right>
        @if ($user)
            <div class="space-y-2">

                <x-input readonly label="Name" :value="$user->name" />
                <x-input readonly label="Email" :value="$user->email" />
                <x-input readonly label="Created At" :value="$user->created_at->format('d/m/Y H:i')" />
                <x-input readonly label="Updated At" :value="$user->updated_at->format('d/m/Y H:i')" />
                <x-input readonly label="Deleted At" :value="$user->deleted_at?->format('d/m/Y H:i') ?? 'N/A'" />
                <x-input readonly label="Deleted By" :value="$user->deletedBy?->name ?? 'N/A'" />
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false" />
        </x-slot:actions>
    </x-drawer>

    <x-button label="Open" @click="$wire.modal = true" />
</div>
