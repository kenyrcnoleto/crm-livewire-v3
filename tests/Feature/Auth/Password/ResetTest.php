<?php

use App\Livewire\Auth\Password;
use App\Livewire\Password\Reset;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\{DB, Notification};
use Livewire\Livewire;

use function Pest\Laravel\get;

test('need to receive a valid token with a combintation with the email', function () {

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
