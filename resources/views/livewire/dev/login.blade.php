<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <x-select  icon="o-user" :options="$this->users" wire:model.live="selectedUser" >
    </x-select>

</div>
