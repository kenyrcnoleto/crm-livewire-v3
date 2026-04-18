<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;

class StopImpersonate extends Component
{
    public function render()
    {
        $user = auth()->user();

        return view('livewire.admin.users.stop-impersonate', ['user' => $user]);
    }

    public function stopImpersonate(): void
    {
        session()->forget('impersonate');

        $this->redirect(route('admin.users'));
        // return redirect()->route('admin.users');
    }

}
