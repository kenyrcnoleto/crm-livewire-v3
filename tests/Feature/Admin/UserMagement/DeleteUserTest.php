<?php

use App\Livewire\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

test('it should be ablet to delete a user', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->set('confirmation_confirmation', 'kenobi')
        ->call('destroy')
        ->assertDispatched('user::deleted');

    assertSoftDeleted('users', [
        'id' => $forDeletion->id,
    ]);
});

test('it should have a confirmation before deletion', function () {
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
         ->set('user', $forDeletion)
         ->call('destroy')
         ->assertHasErrors(['confirmation' => 'confirmed'])
         ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', [
        'id' => $forDeletion->id,
    ]);
});

test('should send a notification to the user telling him that no has no long access to the application', function () {

    Notification::fake();
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    //another way to call the destroy method without dispatching the event, so we can test the notification
    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->set('confirmation_confirmation', 'kenobi')
        ->call('destroy');

    Notification::assertSentTo($forDeletion, \App\Notifications\UserDeletedNotification::class);
});

test('it should not be possible to delete the logged user', function () {
    $user = User::factory()->admin()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
         ->set('user', $user)
         ->set('confirmation_confirmation', 'kenobi')
         ->call('destroy')
         ->assertHasErrors(['confirmation'])
         ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', [
        'id' => $user->id,
    ]);
})->only();
