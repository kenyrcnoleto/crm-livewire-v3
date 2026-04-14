<?php

namespace App\Livewire\Admin\Users;

use Livewire\Attributes\On;
use Livewire\Component;

class Impersonate extends Component
{
    public function render()
    {
        return <<<'HTML'
        <div>
            {{-- Care about people's approval and you will be their prisoner. --}}
        </div>
        HTML;
    }

    #[On('user::impersonation')]
    public function impersonate(int $userId): void
    {
        session()->put('impersonator', auth()->id());
        session()->put('impersonate', $userId);

        $this->redirect(route('dashboard'));

    }
}
