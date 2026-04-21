<?php

use App\Livewire\Dev\Login;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\assertAuthenticatedAs;

test('it should be able to list all users of the system', function () {
    User::factory()->count(10)->create();

    $user = User::all();

    Livewire::test(Login::class)
        ->assertSet('users', $user)
        ->assertSee($user->first()->name);
});

test('it should be able to login with any user', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('selectedUser', $user->id)
        ->call('login')
        ->assertRedirect(route('dashboard'));

    assertAuthenticatedAs($user);
});
