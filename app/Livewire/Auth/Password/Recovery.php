<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Recovery extends Component
{
    public ?string $email = null;

    public ?string $message = null;

    public function render(): View
    {
        return view('livewire.auth.password.recovery');
    }

    public function startPasswordRecovery(): void
    {
        $user = User::whereEmail($this->email)->first();

        $user?->notify(new PasswordRecoveryNotification());

        $this->message = 'You will receive an email with the password recory link.';
    }
}
