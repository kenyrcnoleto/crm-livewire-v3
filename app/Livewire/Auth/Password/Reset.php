<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Hash};
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    public function mount(): void
    {
        $this->token = request('token');
        //dd($this->token);

        if ($this->tokenNotValid()) {

            session()->flash('status', 'Token Invalid');

            $this->redirectRoute('login');
        }
    }
    public function render(): View
    {
        return view('livewire.auth.password.reset');
    }

    private function tokenNotValid()
    {
        $tokens = DB::table('password_reset_tokens')->get(['token']);

        //dd($this->token);
        foreach ($tokens as $key => $t) {

            if (Hash::check($this->token, $t->token)) {
                return false;
            }

        }

        return true;
    }
}
