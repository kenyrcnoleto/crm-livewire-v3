<?php

use App\Livewire\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

test('it should be ablet to restore a user', function () {
    $user           = User::factory()->admin()->create();
    $forRestoration = User::factory()->delete()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Restore::class)
        ->set('user', $forRestoration)
        ->set('confirmation_confirmation', 'dart vader')
        ->call('restore')
        ->assertDispatched('user::restored');

    assertNotSoftDeleted('users', [
        'id' => $forRestoration->id,
    ]);
})->only();

test('it should have a confirmation before restoration', function () {
    $user           = User::factory()->admin()->create();
    $forRestoration = User::factory()->delete()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Restore::class)
         ->set('user', $forRestoration)
         ->call('restore')
         ->assertHasErrors(['confirmation' => 'confirmed'])
         ->assertNotDispatched('user::restored');

    assertSoftDeleted('users', [
        'id' => $forRestoration->id,
    ]);
})->only();

test('should send a notification to the user telling him that no he has again access to the application', function () {

    Notification::fake();
    $user           = User::factory()->admin()->create();
    $forRestoration = User::factory()->delete()->create();

    actingAs($user);

    //another way to call the restore method without dispatching the event, so we can test the notification
    Livewire::test(Admin\Users\Restore::class, ['user' => $forRestoration])
        ->set('confirmation_confirmation', 'dart vader')
        ->call('restore');

    Notification::assertSentTo($forRestoration, \App\Notifications\UserRestoredAccessNotification::class);
})->only();
