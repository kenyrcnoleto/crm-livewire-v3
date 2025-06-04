<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Logout extends Component
{
    public function render(): View
    {
        return view('livewire.auth.logout');
        /*return <<<'HTML'
                <x-button icon="o-power"
                class="btn-circle btn-ghost btn-xs"
                tooltip-left="logoff"
                no-wire-navigate
                 wire:click="logout" />
        HTML;*/
    }

    public function logout(): void
    {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'));
    }
}
