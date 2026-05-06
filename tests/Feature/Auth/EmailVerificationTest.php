<?php

use App\Listeners\Auth\CreateValidationCode;
use App\Livewire\Auth\Register;
use App\Models\User;
use App\Notifications\Auth\ValidationCodeNotification;
use Illuminate\Support\Facades\{Event, Notification};
use Livewire\Livewire;

use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    Notification::fake();
});

describe('after registration', function () {

    test('should create a new validation code and save in the users table', function () {

        $user = User::factory()->create(['email_verified_at' => null, 'validation_code' => null]);

        $event    = new \Illuminate\Auth\Events\Registered($user);
        $listener = new CreateValidationCode();

        $listener->handle($event);

        $user->refresh();

        expect($user)->validation_code->not->toBeNull()
            ->and($user)->validation_code->toBeNumeric();

        assertTrue(str($user->validation_code)->length() === 6);

    });

    test('should send that new code the user via email', function () {
        $user = User::factory()->create(['email_verified_at' => null, 'validation_code' => null]);

        $event    = new \Illuminate\Auth\Events\Registered($user);
        $listener = new CreateValidationCode();

        $listener->handle($event);

        Notification::assertSentTo($user, ValidationCodeNotification::class);
        // Notification::assertSentTO($user, function (ValidationCodeNotification $notification) use ($user) {
        //     return $notification->toMail($user)->subject === 'Your validation code';
        // });
    });

    test('making sure that the listener to send the is code is linked to the Registered event', function () {
        Event::fake();
        Event::assertListening(
            \Illuminate\Auth\Events\Registered::class,
            CreateValidationCode::class
        );
    });
});

describe('validation page', function () {

    test('it should to the validation page after registration', function () {
        Livewire::test(Register::class)
        ->set('name', 'Joe doe')
        ->set('email', 'joe@doe.com')
        ->set('email_confirmation', 'joe@doe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('auth.email-validation'));

    });
});
