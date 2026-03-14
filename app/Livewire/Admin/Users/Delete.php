<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;

class Delete extends Component
{
    public ?User $user = null;

    public bool $modal = false;

    #[Rule(['required', 'confirmed', 'in:kenobi'])]
    public string $confirmation = 'kenobi';

    public ?string $confirmation_confirmation = null;

    public function render(): View
    {
        return view('livewire.admin.users.delete');
    }

    #[On('user::deletion')]
    public function openConfirmationFor(int $userId)
    {
        $this->user  = User::select('id', 'name')->findOrFail($userId);
        $this->modal = true;
    }
    public function destroy()
    {
        $this->validate();
        $this->user->delete();
        $this->user->notify(new \App\Notifications\UserDeletedNotification());
        $this->dispatch('user::deleted');
        $this->reset(['modal', 'confirmation', 'confirmation_confirmation']);

    }
}
