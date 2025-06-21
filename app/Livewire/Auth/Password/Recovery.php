<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

class Recovery extends Component
{
    #[Rule(['email', 'required'])]
    public ?string $email = null;

    public ?string $message = null;

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.password.recovery');
    }

    public function startPasswordRecovery(): void
    {
        $this->validate();

        /* não foi mais necessário uma vez que utilizou Password class
        $user = User::whereEmail($this->email)->first();

        $user?->notify(new PasswordRecoveryNotification());*/
        Password::sendResetLink($this->only('email'));

        $this->message = 'You will receive an email with the password recory link.';
    }
}
