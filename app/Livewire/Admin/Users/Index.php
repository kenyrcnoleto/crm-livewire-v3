<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use App\Models\{Permission, User};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\{Computed, On};
use Livewire\{Component, WithPagination};

/**
* @property-read Collection|User[] $users
*/
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    public array $search_permissions = [];

    public Collection $permissionsToSearch;

    public bool $search_trash = false;

    public string $sortDirection = 'asc';

    public string $sortColumnBy = 'id';

    public int $perPage = 15;

    public function mount()
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
    }

    #[On('user::deleted')]
    #[On('user::restored')]
    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }

    #[Computed()]
    public function users()
    {
        $this->validate(['search_permissions' => 'exists:permissions,id']);

        // dd();
        return User::query()
            ->with('permissions')
            ->when(
                $this->search,
                fn (Builder $q) => $q
                ->where(
                    DB::raw('lower(name)'),
                    'LIKE',
                    '%' . strtolower($this->search) . '%'
                )->orWhere(
                    'email',
                    'like',
                    '%' . strtolower($this->search) . '%'
                )
            )
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas(
                    'permissions',
                    function (Builder $query) {
                        $query->whereIn('id', $this->search_permissions);
                    }
                )
            )
            ->when(
                $this->search_trash,
                fn (Builder $q) => $q->onlyTrashed()
            )
            ->orderBy($this->sortColumnBy, $this->sortDirection)
            ->paginate($this->perPage);

        /* Uma outra forma: fn (Builder $q) => $q->whereRaw(
                '
                ( SELECT COUNT(*)
                FROM permission_user
                WHERE permission_id IN (?)
                AND user_id = users.id) > 0
            ',
                $this->search_permissions
            )*/
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
            ['key' => 'name', 'label' => 'Name', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
            ['key' => 'email', 'label' => 'Email', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
            ['key' => 'permissions', 'label' => 'Permissions ', 'sortColumnBy' => $this->sortColumnBy, 'sortDirection' => $this->sortDirection],
        ];

    }

    #[Computed]
    public function filterPermissions(string $value = null): void
    {

        $this->permissionsToSearch = Permission::query()
            ->when($value, fn (Builder $q) => $q->where('key', 'like', "%$value%"))
            ->orderBy('key')
            ->get();
    }

    public function sortBy(string $column, string $direction)
    {
        //ds($column, $direction);
        $this->sortColumnBy  = $column;
        $this->sortDirection = $direction;
    }

    public function destroy(int $id): void
    {
        $this->dispatch('user::deletion', userId: $id)->to('admin.users.delete');
    }

    public function restore(int $id): void
    {
        $this->dispatch('user::restoring', userId: $id)->to('admin.users.restore');
    }
}
