<div class="flex items-center space-x-2 " >
    {{-- The best athlete wants his opponent at his best. --}}
    <x-select  icon="o-user" :options="$this->users" wire:model.live="selectedUser" placeholder="Select an user">
    </x-select>

    <x-button class="btn-sm" wire:click="login">Login</x-button>

</div>
