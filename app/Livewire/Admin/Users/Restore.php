<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?User $user = null;

    public bool $modal = false;

    #[Rule(['required', 'confirmed', 'in:kenobi'])]
    public string $confirmation = 'kenobi';

    public ?string $confirmation_confirmation = null;

    public function render(): View
    {
        return view('livewire.admin.users.restore');
    }

    #[On('user::restoring')]
    public function openConfirmationFor(int $userId)
    {
        $this->user  = User::select('id', 'name')->withTrashed()->find($userId);
        $this->modal = true;
    }

    public function restore()
    {
        $this->validate();

        if ($this->user->is(auth()->user())) {
            $this->addError('confirmation', 'You cannot restore the logged user');

            return;
        }

        $this->user->restore();
        $this->user->notify(new \App\Notifications\UserRestoredAccessNotification());

        $this->dispatch('user::restored');
        $this->reset(['modal', 'confirmation', 'confirmation_confirmation']);
        $this->success('User restored successfully');

    }
}
