<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\{Component};

class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'email', 'max:255', 'confirmed', 'unique:users,email'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required'])]
    public ?string $password = null;

    public function render(): View
    {

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.auth.register');

        return $view->layout('components.layouts.guest');
    }

    public function submit(): void
    {
        $this->validate();

        /** @var User $user */
        $user = User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,

        ]);

        auth()->login($user);

        $user->notify(new WelcomeNotification());

        $this->redirect(RouteServiceProvider::HOME);
    }
}
