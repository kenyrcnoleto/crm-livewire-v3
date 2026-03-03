<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Delete extends Component
{
    public User $user;

    #[Rule(['required', 'confirmed', 'in:kenobi'])]
    public string $confirmation = 'kenobi';

    public ?string $confirmation_confirmation = null;

    public function render(): View
    {
        return view('livewire.admin.users.delete');
    }

    public function destroy()
    {
        $this->validate();
        $this->user->delete();
        $this->dispatch('user::deleted');

    }
}
