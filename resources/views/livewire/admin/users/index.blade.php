<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <x-header title="Users" separator />

    <div class="flex space-x-4 mb-4">

        <div class="mb-4">
            <x-input
            label="Search bya email or name"
            icon="o-magnifying-glass"
             placeholder="Search by email and name"
             wire:model.live="search"
             />
        </div>

        <x-choices
                label="Search by permissions"
                placeholder="Filter by Permissions"
                wire:model.live="search_permissions"
                :options="$permissionsToSearch"
                option-label="key"
                search-funcion="filterPermissions"
                no-result-text="Ops! Nothing here ..."

        />

        <x-checkbox
             label="Show Deleted Users"
             wire:model.live="search_trash"
             class="checkbox-primary"
             right tight />
        <hr />

    </div>
   <x-table :headers="$this->headers" :rows="$this->users" >
    @scope('cell_permissions', $user)
        @foreach ($user->permissions as $permission)

            <x-badge value="{{$permission->key}}" class="badge-primary" />
        @endforeach

    @endscope

    @scope('actions', $user)
    @unless ($user->trashed())
        <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" />
    @else
        <x-button icon="o-arrow-path-rounded-square" wire:click="delete({{ $user->id }})" spinner class="btn-sm btn-success btn-ghost" />
    @endunless
    @endscope
   </x-table>
</div>
