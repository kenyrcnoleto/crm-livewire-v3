<?php

use App\Livewire\Auth\Password;
use App\Livewire\Password\Reset;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\{DB, Hash, Notification};
use Livewire\Livewire;

use function Pest\Laravel\get;
use function PHPUnit\Framework\assertTrue;

test('need to receive a valid token with a combintation with the email and open the page', function () {

    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Password\Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    //é o hash é feito na notificação
    /*$token = DB::table('password_reset_tokens')
            ->where('email', '=', $user->email)
            ->first();*/

    Notification::assertSentTo(
        $user,
        ResetPassword::class,
        function (ResetPassword $notification) {
            get(route('password.reset') . '?token=' . $notification->token)
               ->assertSuccessful();

            get(route('password.reset') . '?token=any->token')
            ->assertRedirect(route('login'));

            return true;
        }
    );

    // dump($token->token);
    //-> passando as variáveis via mount
    //Livewire::test(Password\Reset::class, ['token' => $token->token, 'email' => $token->email])

    //opção valida para passar parametros no na url
    //get(route('password.reset') .'?token='. $token->token)

});

test('test if is possibie to reset the password with the given token', function () {
    Notification::fake();

    $user = User::factory()->create();

    //dd(Hash::check('password', $user->password));

    Livewire::test(Password\Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    //é o hash é feito na notificação
    /*$token = DB::table('password_reset_tokens')
            ->where('email', '=', $user->email)
            ->first();*/

    Notification::assertSentTo(
        $user,
        ResetPassword::class,
        function (ResetPassword $notification) use ($user) {

            Livewire::test(
                Password\Reset::class,
                ['token' => $notification->token, 'email' => $user->email]
            )
                ->set('email_confirmation', $user->email)
                ->set('password', 'new_password')
                ->set('password_confirmation', 'new_password')
                ->call('updatePassword')
                ->assertHasNoErrors()
                ->assertRedirect(route('dashboard'));

            $user->refresh();

            assertTrue(
                Hash::check('new_password', $user->password)
            );

            return true;
        }
    );
});

test('cheking form rules', function ($validation) {
    Notification::fake();

    //dd($f);
    $user = User::factory()->create();

    Livewire::test(Password\Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    Notification::assertSentTo(
        $user,
        ResetPassword::class,
        function (ResetPassword $notification) use ($user, $validation) {
            //()->attributes = ['token' => 'jeremias'];

            Livewire::test(Password\Reset::class, ['token' => $notification->token, 'email' => $user->email])
               ->set($validation->field, $validation->value)
               ->call('updatePassword')
               ->assertHasErrors([$validation->field => $validation->rule]);

            return true;
        }
    );

})->with([
    //'token:required' => (object)['field' => 'token', 'value' => '', 'rule' => 'required'],
    'email:required'     => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email:confirmed'    => (object)['field' => 'email', 'value' => 'email@email.com', 'rule' => 'confirmed'],
    'email:email'        => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'password:required'  => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password:confirmed' => (object)['field' => 'password', 'value' => 'any-password', 'rule' => 'confirmed'],
]);

test('needs to show an obfuscate email to the user', function () {
    $email           = 'testinng@exemple.com';
    $obfuscatedEmail = obfuscate_email($email);

    expect($obfuscatedEmail)
        ->toBe('te******@********com');

    // --

    Notification::fake();

    //dd($f);
    $user = User::factory()->create();

    Livewire::test(Password\Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    Notification::assertSentTo(
        $user,
        ResetPassword::class,
        function (ResetPassword $notification) use ($user) {
            //()->attributes = ['token' => 'jeremias'];

            Livewire::test(Password\Reset::class, ['token' => $notification->token, 'email' => $user->email])
               ->assertSet('obfucatedEmail', obfuscate_email($user->email));

            return true;
        }
    );
});
