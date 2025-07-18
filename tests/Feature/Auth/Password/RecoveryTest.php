<?php

use App\Livewire\Auth\Password\Recovery;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, get};

test('needs to have a route to password recovery', function () {
    get(route('password.recovery'))
        ->assertSeeLivewire('auth.password.recovery');
});

test('it should be able to request for a password recovery sending notification to the user', function () {
    Notification::fake();
    /**@var User $user */
    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->assertDontSee('You will receive an email with the password recovery link.')
        ->set('email', $user->email)
        ->call('startPasswordRecovery')
        ->assertSee('You will receive an email with the password recory link.');

    Notification::assertSentTo($user, ResetPassword::class);

});

test('it making sure the email is a real email', function ($f) {

    Livewire::test(Recovery::class)
       ->set('email', $f->value)
       ->call('startPasswordRecovery')
       ->assertHasErrors(['email' => $f->rule]);
})->with([
    'required' => (object)['value' => '', 'rule' => 'required'],
    'email'    => (object)['value' => 'any email', 'rule' => 'email'],
]);

test('needs to create a token when request for a password recovery', function () {
    /**@var User $user */
    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);

});
