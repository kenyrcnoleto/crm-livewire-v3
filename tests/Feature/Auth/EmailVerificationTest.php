<?php

use App\Listeners\Auth\CreateValidationCode;
use App\Models\User;

use function PHPUnit\Framework\assertTrue;

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

})->todo();

test('making sure that the listener to send the is code is linked to the Registered event', function () {

})->todo();
