<?php

use App\Livewire\Admin\Users\Impersonate;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

test('it should add a key impersonate to the session with the given user', function () {

    $user = User::factory()->create();

    Livewire::test(Impersonate::class)
            ->call('impersonate', $user->id);

    assertTrue(session()->has('impersonate'));

    assertSame(session()->get('impersonate'), $user->id);
});

test('should make sure that we are logged with the impersonate user', function () {
    $admin = User::factory()->admin()->create();
    $user  = User::factory()->create();

    actingAs($admin);

    expect(auth()->id())->toBe($admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id);

    get(route('dashboard'))
        ->assertSee(__("You're impersonating :name, click here to stop impersonating.", ['name' => $user->name]));

    expect(auth()->id())->toBe($user->id);
});
