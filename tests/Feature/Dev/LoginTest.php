<?php

use App\Livewire\Dev\Login;
use App\Models\User;
use Livewire\Livewire;

test('it should be able to list all users of the system', function () {
    User::factory()->count(10)->create();

    $user = User::all();

    Livewire::test(Login::class)
        ->assertSet('users', $user)
        ->assertSee($user->first()->name);
})->only();
