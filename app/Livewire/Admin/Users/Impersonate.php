<?php

namespace App\Livewire\Admin\Users;

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

    public function impersonate(int $id): void
    {
        session()->put('impersonate', $id);

    }
}
