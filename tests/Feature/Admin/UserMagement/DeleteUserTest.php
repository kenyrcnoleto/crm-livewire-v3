<?php

use App\Livewire\Admin;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

test('it should be ablet to delete a user', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->call('destroy')
        ->assertDispatched('user::deleted');

    assertSoftDeleted('users', [
        'id' => $forDeletion->id,
    ]);
})->only();
