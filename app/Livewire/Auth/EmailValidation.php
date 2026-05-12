<?php

namespace App\Livewire\Auth;

use App\Events\SendNewCode;
use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EmailValidation extends Component
{
    public $code = null;

    public function render(): View
    {
        return view('livewire.auth.email-validation');
    }

    public function handle()
    {
        $this->validate([
            'code' => function (string $attribute, mixed $value, Closure $fail) {
                if ($value !== auth()->user()->validation_code) {
                    $fail("The code is invalid.");
                }
            },
        ]);
    }

    public function sendNewCode()
    {

        SendNewCode::dispatch(
            auth()->user()
        );

    }
}
